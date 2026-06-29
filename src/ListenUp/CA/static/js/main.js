document.addEventListener('DOMContentLoaded', () => {
    const textInput = document.getElementById('text-input');
    const generateBtn = document.getElementById('generate-btn');
    const resultSection = document.getElementById('result-section');
    const ipaOutput = document.getElementById('ipa-output');
    const audioPlayer = document.getElementById('audio-player');
    const downloadBtn = document.getElementById('download-btn');
    const copyIpaBtn = document.getElementById('copy-ipa');

    const generate = async () => {
        const text = textInput.value.trim();
        if (!text) return;

        // Show loading state
        generateBtn.classList.add('loading');
        generateBtn.disabled = true;

        try {
            const response = await fetch('/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ text }),
            });

            if (!response.ok) {
                throw new Error('Failed to generate content');
            }

            const data = await response.json();

            // Update UI
            ipaOutput.textContent = data.ipa;
            audioPlayer.src = data.audio_url;
            downloadBtn.href = data.audio_url;

            resultSection.classList.remove('hidden');
            
            // Auto play audio
            audioPlayer.play();

        } catch (error) {
            console.error('Error:', error);
            alert('Something went wrong. Please try again.');
        } finally {
            generateBtn.classList.remove('loading');
            generateBtn.disabled = false;
        }
    };

    generateBtn.addEventListener('click', generate);

    // Enter key to generate
    textInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            generate();
        }
    });

    // Copy IPA to clipboard
    copyIpaBtn.addEventListener('click', () => {
        const ipaText = ipaOutput.textContent;
        navigator.clipboard.writeText(ipaText).then(() => {
            const icon = copyIpaBtn.querySelector('i');
            icon.classList.remove('far', 'fa-copy');
            icon.classList.add('fas', 'fa-check');
            
            setTimeout(() => {
                icon.classList.remove('fas', 'fa-check');
                icon.classList.add('far', 'fa-copy');
            }, 2000);
        });
    });
});
