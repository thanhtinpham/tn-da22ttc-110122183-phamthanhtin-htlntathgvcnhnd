import sys
import json
import os
import uuid
import argparse
from gtts import gTTS
from ipa_helper import convert_text_to_ipa

if __name__ == "__main__":
    # Force UTF-8 encoding for stdout
    sys.stdout.reconfigure(encoding='utf-8')
    
    parser = argparse.ArgumentParser(description="ListenUp Audio Converter CLI")
    parser.add_argument("action", type=str, help="Action to perform (convert)")
    parser.add_argument("text", type=str, help="Text to translate/convert")
    parser.add_argument("--accent", type=str, default="en-US", help="Accent to use (en-US, en-GB, en-AU)")
    parser.add_argument("--speed", type=float, default=1.0, help="Speed factor (e.g. 0.75, 1.0, 1.25, 1.5)")
    parser.add_argument("--gender", type=str, default="female", help="Gender of the voice (female, male)")

    try:
        # Ignore unknown arguments gracefully to avoid crash if passed by Laravel
        args, unknown = parser.parse_known_args()
    except Exception as e:
        print(json.dumps({"error": f"Argument parsing error: {str(e)}"}))
        sys.exit(1)
        
    action = args.action
    text = args.text.strip()
    accent = args.accent
    speed = args.speed
    gender = args.gender.lower() if args.gender else "female"
    
    if not text:
        print(json.dumps({"error": "Text is empty"}))
        sys.exit(1)
        
    if action == "convert":
        try:
            # Generate IPA transcription
            ipa_text = convert_text_to_ipa(text)
            
            # Determine voice name for edge-tts
            voice_map = {
                'en-US': {
                    'female': 'en-US-JennyNeural',
                    'male': 'en-US-GuyNeural'
                },
                'en-GB': {
                    'female': 'en-GB-SoniaNeural',
                    'male': 'en-GB-RyanNeural'
                },
                'en-AU': {
                    'female': 'en-AU-NatashaNeural',
                    'male': 'en-AU-WilliamNeural'
                }
            }
            accent_voices = voice_map.get(accent, voice_map['en-US'])
            voice_name = accent_voices.get(gender, accent_voices['female'])
            
            # Format speed rate for edge-tts (e.g. "+0%", "-20%", "+25%")
            rate_val = int((speed - 1.0) * 100)
            rate_str = f"{rate_val:+d}%"
            
            # Generate Audio using edge-tts with gTTS fallback
            filename = f"{uuid.uuid4().hex}.mp3"
            public_dir = os.path.join(os.path.dirname(os.path.dirname(__file__)), "public", "audio")
            os.makedirs(public_dir, exist_ok=True)
            filepath = os.path.join(public_dir, filename)
            
            audio_generated = False
            try:
                import asyncio
                import edge_tts
                
                async def run_tts():
                    communicate = edge_tts.Communicate(text=text, voice=voice_name, rate=rate_str)
                    await communicate.save(filepath)
                
                asyncio.run(run_tts())
                audio_generated = True
            except Exception as e:
                # Fallback to gTTS will handle it if edge-tts fails
                pass
            
            if not audio_generated:
                tld_map = {
                    'en-US': 'com',
                    'en-GB': 'co.uk',
                    'en-AU': 'com.au'
                }
                tld_value = tld_map.get(accent, 'com')
                slow_mode = (speed < 0.9)
                tts = gTTS(text=text, lang='en', tld=tld_value, slow=slow_mode)
                tts.save(filepath)
            
            # Web accessible URL
            audio_url = f"/audio/{filename}"
            
            print(json.dumps({
                "ipa": ipa_text,
                "audio_url": audio_url
            }))
        except Exception as e:
            print(json.dumps({"error": str(e)}))
            sys.exit(1)
