import os
import sys
import io
import uuid
import logging
import json
from fastapi import FastAPI, Request, HTTPException
from fastapi.responses import HTMLResponse, JSONResponse
from fastapi.staticfiles import StaticFiles
from fastapi.templating import Jinja2Templates
from pydantic import BaseModel
from typing import List, Optional
from gtts import gTTS
from ipa_helper import convert_text_to_ipa
import google.generativeai as genai

# Fix encoding issues on Windows terminal
if sys.stdout.encoding != 'utf-8':
    sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

app = FastAPI()

# Ensure static directories exist
os.makedirs("static/audio", exist_ok=True)
os.makedirs("static/css", exist_ok=True)
os.makedirs("static/js", exist_ok=True)

# Mount static files
app.mount("/static", StaticFiles(directory="static"), name="static")

# Templates setup
templates = Jinja2Templates(directory="templates")

# Helper function to load GEMINI_API_KEY from environment or parent Laravel .env
def get_gemini_api_key():
    key = os.environ.get("GEMINI_API_KEY")
    if key:
        return key
    
    # Try reading from parent directory's .env (Laravel root)
    try:
        parent_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
        env_path = os.path.join(parent_dir, ".env")
        if os.path.exists(env_path):
            with open(env_path, "r", encoding="utf-8") as f:
                for line in f:
                    if line.strip().startswith("GEMINI_API_KEY="):
                        val = line.split("=", 1)[1].strip()
                        # Strip quotes
                        if val.startswith(('"', "'")) and val.endswith(('"', "'")):
                            val = val[1:-1]
                        return val
    except Exception as e:
        logger.warning(f"Không thể đọc file .env ở thư mục gốc: {e}")
    return None

# Configure Gemini API
api_key = get_gemini_api_key()
if api_key:
    genai.configure(api_key=api_key)
    logger.info("Đã cấu hình thành công Gemini API Key.")
else:
    logger.warning("CẢNH BÁO: Chưa tìm thấy GEMINI_API_KEY. Vui lòng thiết lập trong file .env hoặc biến môi trường.")

# Models for existing features
class TextRequest(BaseModel):
    text: str

# --- MODELS FOR NEW GEMINI AI ASSISTANT FEATURES ---

# CHỨC NĂNG 1: Chatbot Hỏi đáp
class ChatRequest(BaseModel):
    user_message: str
    context: Optional[str] = None

# CHỨC NĂNG 2: Tóm tắt bài nghe
class SummaryRequest(BaseModel):
    transcript: str

# CHỨC NĂNG 3: Cá nhân hóa lộ trình học
class PersonalizedRouteRequest(BaseModel):
    scores: List[float]               # Điểm số bài nghe
    history: List[str]                # Lịch sử học tập
    completed_topics: List[str]       # Chủ đề đã học
    weak_topics: List[str]            # Chủ đề còn yếu
    replays: int                      # Số lần nghe lại
    completion_rate: float            # Tỷ lệ hoàn thành bài học (0.0 -> 1.0)
    study_time: float                 # Thời gian học tập (phút)

# --- ENDPOINTS ---

@app.get("/", response_class=HTMLResponse)
async def read_root(request: Request):
    return templates.TemplateResponse(request=request, name="index.html")

@app.post("/generate")
async def generate_content(request: TextRequest):
    text = request.text.strip()
    if not text:
        logger.warning("Empty text received")
        raise HTTPException(status_code=400, detail="Text is empty")

    logger.info(f"Generating IPA and Audio for: {text}")
    try:
        # Generate IPA
        ipa_text = convert_text_to_ipa(text)
        logger.info(f"IPA Generated: {ipa_text}")
        
        # Generate Audio using gTTS
        filename = f"{uuid.uuid4().hex}.mp3"
        filepath = os.path.join("static/audio", filename)
        
        tts = gTTS(text=text, lang='en')
        tts.save(filepath)
        
        audio_url = f"/static/audio/{filename}"
        logger.info(f"Audio generated at: {audio_url}")
        
        return {
            "ipa": ipa_text,
            "audio_url": audio_url
        }
    except Exception as e:
        logger.error(f"Error generating content: {e}", exc_info=True)
        raise HTTPException(status_code=500, detail=str(e))

# CHỨC NĂNG 1: CHATBOT HỎI ĐÁP
@app.post("/chatbot/chat")
async def chatbot_chat(request: ChatRequest):
    message = request.user_message.strip()
    if not message:
        raise HTTPException(status_code=400, detail="Tin nhắn không được để trống")
    
    system_instruction = (
        "Bạn là một trợ lý ảo học tập tiếng Anh thân thiện, nhiệt tình của hệ thống ListenUp.\n"
        "Hãy thực hiện các nhiệm vụ sau dựa trên câu hỏi của học viên:\n"
        "- Trả lời câu hỏi của học viên.\n"
        "- Giải thích từ vựng tiếng Anh chi tiết nhưng dễ hiểu.\n"
        "- Giải thích cấu trúc ngữ pháp có trong câu hỏi hoặc bài học.\n"
        "- Dịch các câu tiếng Anh sang tiếng Việt nếu học viên yêu cầu.\n"
        "- Đưa ra các ví dụ minh họa tiếng Anh sinh động kèm bản dịch.\n"
        "- Giải thích nội dung hoặc giải đáp các thắc mắc về bài nghe.\n\n"
        "ĐẶC BIỆT KHI HỌC VIÊN HỎI VỀ BÀI TEST, CHỦ ĐỀ HOẶC CẤP ĐỘ CỦA HỆ THỐNG:\n"
        "Nếu học viên muốn tìm bài test, chủ đề hay cấp độ để học, hãy đối chiếu với thông tin trong ngữ cảnh hệ thống cung cấp và LUÔN LUÔN tạo ra các nút liên kết HTML cụ thể dưới đây (tuyệt đối không để link thô hay chỉ dùng văn bản thường):\n"
        "- Nút làm bài test: <a href=\"/user/test/{Mã bài test}\" class=\"inline-flex items-center gap-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black px-3 py-1.5 rounded-xl text-[10px] uppercase tracking-wider no-underline shadow-md shadow-indigo-500/10 hover:scale-102 transition-all mt-2 mr-2\"><i class=\"fas fa-play text-[9px]\"></i> Luyện ngay: {Tên bài test}</a>\n"
        "- Nút chọn chủ đề: <a href=\"/topics/{Mã chủ đề}\" class=\"inline-flex items-center gap-1.5 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-black px-3 py-1.5 rounded-xl text-[10px] uppercase tracking-wider no-underline shadow-md shadow-rose-500/10 hover:scale-102 transition-all mt-2 mr-2\"><i class=\"fas fa-tag text-[9px]\"></i> Chủ đề: {Tên chủ đề}</a>\n"
        "- Nút chọn cấp độ: <a href=\"/levels/{Mã cấp độ}\" class=\"inline-flex items-center gap-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-black px-3 py-1.5 rounded-xl text-[10px] uppercase tracking-wider no-underline shadow-md shadow-emerald-500/10 hover:scale-102 transition-all mt-2 mr-2\"><i class=\"fas fa-layer-group text-[9px]\"></i> Cấp độ: {Tên cấp độ}</a>\n\n"
        "Yêu cầu:\n"
        "1. Trả lời bằng tiếng Việt.\n"
        "2. Câu trả lời cần ngắn gọn, cô đọng, đi thẳng vào vấn đề.\n"
        "3. Ngôn từ dễ hiểu, gần gũi, thân thiện và mang tính khuyến khích học tập."
    )
    
    prompt = f"System Instruction: {system_instruction}\n"
    if request.context:
        prompt += f"Ngữ cảnh bài học hiện tại: {request.context}\n"
    prompt += f"Học viên hỏi: {message}\nTrợ lý trả lời:"
    
    try:
        model = genai.GenerativeModel("gemini-2.5-flash")
        response = model.generate_content(prompt)
        ai_reply = response.text.strip()
        
        return {"reply": ai_reply}
    except Exception as e:
        logger.error(f"Lỗi endpoint chatbot/chat: {e}", exc_info=True)
        raise HTTPException(status_code=500, detail=f"Lỗi gọi Gemini API: {str(e)}")

# CHỨC NĂNG 2: TÓM TẮT BÀI NGHE
@app.post("/chatbot/summarize")
async def chatbot_summarize(request: SummaryRequest):
    transcript = request.transcript.strip()
    if not transcript:
        raise HTTPException(status_code=400, detail="Transcript bài nghe không được để trống")
        
    prompt = f"""
Hãy đóng vai trò là một giảng viên tiếng Anh phân tích transcript bài nghe dưới đây.

Transcript bài nghe:
\"\"\"
{transcript}
\"\"\"

Nhiệm vụ của bạn là phân tích và trả về kết quả dưới dạng JSON duy nhất, có cấu trúc chính xác như sau:
{{
  "summary": "Tóm tắt ngắn gọn nội dung cốt lõi của bài nghe bằng tiếng Việt (tối đa 3 câu)",
  "topic": "Chủ đề chính của bài nghe (ví dụ: Công nghệ, Giao tiếp, Du lịch, v.v. - dịch sang tiếng Việt)",
  "keyVocabulary": [
    {{
      "word": "từ vựng tiếng Anh quan trọng xuất hiện trong bài",
      "meaning": "nghĩa tiếng Việt tương ứng kèm giải thích ngắn gọn"
    }}
  ],
  "grammarPatterns": [
    {{
      "pattern": "cấu trúc ngữ pháp tiếng Anh nổi bật cần chú ý",
      "explanation": "giải thích ý nghĩa và cách sử dụng bằng tiếng Việt"
    }}
  ],
  "cefrLevel": "Đánh giá độ khó theo thang CEFR (chỉ điền một trong các giá trị: A1, A2, B1, B2, C1, C2)"
}}

Yêu cầu:
- Trả về DUY NHẤT một chuỗi JSON hợp lệ.
- Không viết bất kỳ lời dẫn nhập nào, không dùng block markdown bọc ngoài ngoài dữ liệu JSON.
- Đảm bảo viết đúng định dạng JSON để có thể phân tích bằng hàm json.loads().
"""
    try:
        model = genai.GenerativeModel("gemini-2.5-flash")
        response = model.generate_content(
            prompt,
            generation_config={"response_mime_type": "application/json"}
        )
        # Parse JSON
        result_json = json.loads(response.text.strip())
        return result_json
    except Exception as e:
        logger.error(f"Lỗi endpoint chatbot/summarize: {e}", exc_info=True)
        raise HTTPException(status_code=500, detail=f"Lỗi xử lý tóm tắt bài nghe: {str(e)}")

# CHỨC NĂNG 3: CÁ NHÂN HÓA LỘ TRÌNH HỌC
@app.post("/chatbot/personalize")
async def chatbot_personalize(request: PersonalizedRouteRequest):
    prompt = f"""
Hãy đóng vai trò là một Chuyên gia Cố vấn Học tập (Study Advisor) thông minh. Dựa trên dữ liệu thống kê kết quả và tiến trình học tập của học viên dưới đây, hãy lập báo cáo phân tích và lộ trình học cá nhân hóa.

Dữ liệu học tập:
- Điểm số các bài nghe gần đây: {request.scores}
- Lịch sử học tập gần đây: {request.history}
- Các chủ đề đã hoàn thành: {request.completed_topics}
- Các chủ đề còn yếu (cần cải thiện): {request.weak_topics}
- Số lần nghe lại trung bình: {request.replays} lần
- Tỷ lệ hoàn thành bài học: {request.completion_rate * 100}%
- Tổng thời gian học tập tích lũy: {request.study_time} phút

Nhiệm vụ của bạn là phân tích hiệu suất học tập, điểm mạnh, điểm yếu, trình độ hiện tại, dự đoán mức độ tiến bộ, đề xuất bài học tiếp theo và lập kế hoạch học tập trong 7 ngày tới.

Hãy trả về kết quả dưới dạng JSON duy nhất, có cấu trúc chính xác như sau:
{{
  "strengths": [
    "điểm mạnh 1 của học viên (bằng tiếng Việt, ví dụ: kiên trì luyện nghe lại, điểm số ổn định, v.v.)",
    "điểm mạnh 2..."
  ],
  "weaknesses": [
    "điểm yếu cần khắc phục (bằng tiếng Việt, ví dụ: chủ đề học chưa đa dạng, tốc độ nghe còn chậm, v.v.)",
    "điểm yếu 2..."
  ],
  "currentLevel": "Đánh giá tổng quát trình độ hiện tại của học viên (ví dụ: Sơ cấp A2, Trung cấp B1, v.v. kèm lời giải thích ngắn gọn)",
  "recommendedLessons": [
    "Bài học/Chủ đề gợi ý tiếp theo phù hợp với trình độ và cải thiện điểm yếu"
  ],
  "weeklyPlan": [
    "Ngày 1: Mô tả nhiệm vụ học tập cụ thể",
    "Ngày 2: Mô tả nhiệm vụ học tập cụ thể",
    "Ngày 3: Mô tả nhiệm vụ học tập cụ thể",
    "Ngày 4: Mô tả nhiệm vụ học tập cụ thể",
    "Ngày 5: Mô tả nhiệm vụ học tập cụ thể",
    "Ngày 6: Mô tả nhiệm vụ học tập cụ thể",
    "Ngày 7: Mô tả nhiệm vụ học tập cụ thể"
  ],
  "suggestions": [
    "Gợi ý/Lời khuyên học tập số 1 để nâng cao kỹ năng nghe hiệu quả",
    "Gợi ý/Lời khuyên học tập số 2..."
  ]
}}

Yêu cầu:
- Trả về DUY NHẤT một chuỗi JSON hợp lệ.
- Không viết bất kỳ lời dẫn nhập nào.
- Đảm bảo viết đúng định dạng JSON để có thể phân tích bằng hàm json.loads().
"""
    try:
        model = genai.GenerativeModel("gemini-2.5-flash")
        response = model.generate_content(
            prompt,
            generation_config={"response_mime_type": "application/json"}
        )
        # Parse JSON
        result_json = json.loads(response.text.strip())
        return result_json
    except Exception as e:
        logger.error(f"Lỗi endpoint chatbot/personalize: {e}", exc_info=True)
        raise HTTPException(status_code=500, detail=f"Lỗi xử lý lộ trình cá nhân hóa: {str(e)}")

if __name__ == "__main__":
    import uvicorn
    logger.info("Starting server on http://localhost:8001")
    uvicorn.run(app, host="0.0.0.0", port=8001)

