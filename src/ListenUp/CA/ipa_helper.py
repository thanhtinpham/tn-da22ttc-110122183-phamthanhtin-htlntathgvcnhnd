import urllib.request
import json
import os
import re
import eng_to_ipa as ipa

CACHE_FILE = os.path.join(os.path.dirname(__file__), "ipa_cache.json")

# Load cache
if os.path.exists(CACHE_FILE):
    try:
        with open(CACHE_FILE, "r", encoding="utf-8") as f:
            ipa_cache = json.load(f)
    except Exception:
        ipa_cache = {}
else:
    ipa_cache = {}

def save_cache():
    try:
        with open(CACHE_FILE, "w", encoding="utf-8") as f:
            json.dump(ipa_cache, f, ensure_ascii=False, indent=2)
    except Exception as e:
        print(f"Error saving cache: {e}")

def get_word_ipa_online(word):
    """
    Fetch US/UK IPA for a single word from api.dictionaryapi.dev
    Returns a dict with {'uk': '...', 'us': '...'} or None
    """
    word_clean = word.lower().strip()
    if not word_clean:
        return None
        
    url = f"https://api.dictionaryapi.dev/api/v2/entries/en/{word_clean}"
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        with urllib.request.urlopen(req, timeout=5) as response:
            data = json.loads(response.read().decode('utf-8'))
            if not data or not isinstance(data, list):
                return None
                
            phonetics = data[0].get("phonetics", [])
            uk_ipa = None
            us_ipa = None
            unassigned = []
            
            for p in phonetics:
                text = p.get("text")
                if not text:
                    continue
                audio = p.get("audio", "").lower()
                
                # Standardize IPA chars
                text = text.replace("ɹ", "r")
                
                # Ensure slashes are present
                if not text.startswith("/"):
                    text = f"/{text}"
                if not text.endswith("/"):
                    text = f"{text}/"
                    
                if "-uk" in audio:
                    uk_ipa = text
                elif "-us" in audio or "-au" in audio:
                    us_ipa = text
                
                if text not in unassigned:
                    unassigned.append(text)
            
            if not uk_ipa and len(unassigned) > 0:
                uk_ipa = unassigned[0]
            if not us_ipa and len(unassigned) > 1:
                us_ipa = unassigned[1]
            elif not us_ipa and len(unassigned) > 0:
                us_ipa = unassigned[0]
                
            if uk_ipa or us_ipa:
                return {
                    'uk': uk_ipa or us_ipa,
                    'us': us_ipa or uk_ipa
                }
    except Exception:
        # Silently fail and fallback
        return None
    return None

def get_word_ipa(word):
    """
    Get IPA for a single word, checking cache first, then online API, then falling back.
    """
    word_clean = re.sub(r'[^a-zA-Z\']', '', word).lower().strip()
    if not word_clean:
        return {'uk': '', 'us': ''}
        
    # Check cache
    if word_clean in ipa_cache:
        return ipa_cache[word_clean]
        
    # Query online
    res = get_word_ipa_online(word_clean)
    if res:
        ipa_cache[word_clean] = res
        save_cache()
        return res
        
    # Offline fallback using eng_to_ipa
    try:
        fallback_val = ipa.convert(word_clean)
        if fallback_val and fallback_val != word_clean + "*":
            # eng_to_ipa doesn't output slashes, let's add them
            if not fallback_val.startswith("/"):
                fallback_val = f"/{fallback_val}"
            if not fallback_val.endswith("/"):
                fallback_val = f"{fallback_val}/"
            res = {'uk': fallback_val, 'us': fallback_val}
            # Cache it
            ipa_cache[word_clean] = res
            save_cache()
            return res
    except Exception:
        pass
        
    # Absolute fallback
    fallback_slash = f"/{word_clean}/"
    return {'uk': fallback_slash, 'us': fallback_slash}

def convert_text_to_ipa(text):
    """
    Converts English text (word, phrase, or sentence) to standard UK and US IPA.
    """
    text = text.strip()
    if not text:
        return ""
        
    # Split text into tokens (words and punctuation/spaces)
    tokens = re.split(r"(\s+|[^\w'])", text)
    
    uk_tokens = []
    us_tokens = []
    
    for token in tokens:
        if not token:
            continue
        if re.match(r"^\s+$", token):
            uk_tokens.append(token)
            us_tokens.append(token)
        elif re.match(r"^[^\w']+$", token):
            uk_tokens.append(token)
            us_tokens.append(token)
        else:
            # It's a word
            ipas = get_word_ipa(token)
            uk_tokens.append(ipas['uk'].strip('/'))
            us_tokens.append(ipas['us'].strip('/'))
            
    uk_phrase = "".join(uk_tokens).strip()
    us_phrase = "".join(us_tokens).strip()
    
    # If the text is just a single word (no spaces), we can return a simpler format
    if " " not in text:
        # Check if UK and US are identical
        if uk_phrase == us_phrase:
            return f"/{uk_phrase}/"
        else:
            return f"UK: /{uk_phrase}/ • US: /{us_phrase}/"
            
    # For sentences/phrases
    if uk_phrase == us_phrase:
        return f"/{uk_phrase}/"
    else:
        return f"UK: /{uk_phrase}/\nUS: /{us_phrase}/"

if __name__ == "__main__":
    import sys
    sys.stdout.reconfigure(encoding='utf-8')
    print("Testing 'car':", convert_text_to_ipa("car"))
    print("Testing 'hello':", convert_text_to_ipa("hello"))
    print("Testing 'welcome home':", convert_text_to_ipa("welcome home"))
