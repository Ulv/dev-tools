const form = document.getElementById('json_form');
const jsonInput = document.getElementById('json_text');
const errorElement = document.getElementById('error')

if (form !== null) {
    errorElement.style.display = 'none';
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const jsonError = validateJson(jsonInput.value.trim());
        if (jsonError === '') {
            form.submit();
        } else {
            errorElement.innerHTML = jsonError;
            errorElement.style.display = 'inline-block';
        }
    });
}

function validateJson(jsonString) {
    try {
        JSON.parse(jsonString);
    } catch (error) {
        if (error instanceof SyntaxError) {
            const match = error.message.match(/at position (\d+)/);
            const line = match ? parseInt(match[1], 10) : 0;

            return `JSON error at line ${line}: ${error.message}`;
        }
    }
    return '';
}
