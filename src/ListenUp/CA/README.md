# AI English Text to Audio + IPA

A modern, simple web application built with FastAPI to convert English text into IPA (International Phonetic Alphabet) and natural-sounding audio.

## Features

- **Text to IPA**: Instant transcription of English text.
- **Text to Speech**: High-quality audio generation using Google TTS.
- **Premium UI**: Modern dark mode with glassmorphism effects.
- **Interactive**: Auto-play, loading animations, and copy-to-clipboard functionality.

## Project Structure

```
project/
│
├── app.py              # FastAPI Backend
├── requirements.txt    # Python Dependencies
├── templates/
│   └── index.html      # Frontend HTML
├── static/
│   ├── css/            # Modern Styling
│   ├── js/             # Interactive Logic
│   └── audio/          # Generated Audio Storage
└── README.md
```

## How to Run

1. **Install Python**: Ensure you have Python 3.8+ installed.
2. **Install Dependencies**:
   ```bash
   pip install -r requirements.txt
   ```
3. **Run the Application**:
   ```bash
   python app.py
   ```
4. **Access Website**: Open your browser and go to `http://127.0.0.1:8000`

## Requirements

- `fastapi`
- `uvicorn`
- `gTTS` (Google Text-to-Speech)
- `eng_to_ipa` (No system dependencies required)
- `jinja2`
