function copy2Clipboard() {
    let code = document.getElementById("code").innerText;

    if (typeof navigator.clipboard !== "undefined") {
        navigator.clipboard.writeText(code);
    } else {
        alert("Serve tools through HTTPS to use clipboard copy functionality")
    }
}

function copySwagger() {
    const copyText = document.getElementById("swaggerOutput").textContent;
    const textArea = document.createElement("textarea");
    textArea.textContent = copyText;
    document.body.append(textArea);
    textArea.select();
    document.execCommand("copy");
    textArea.remove();
}
