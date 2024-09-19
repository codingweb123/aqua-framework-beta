<?php
/**
 * @param $any
 * @return void
 */
function ddd($any): void
{
    $any = urlencoded_recursive($any);
    $debugString = json_encode(["status" => "debug/error", "message" => $any], 1);
    die('<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>AQUA | Dump Die Debug</title>
            <style>
                @import url("https://fonts.googleapis.com/css2?family=Inconsolata:wght@200;300;400;500&display=swap");
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                :root {
                    --width: 1024px;
                    --primary: #A09F9F;
                    --background: #212121;
                    --background2: #181818;
                    --background3: #1E1C1C;
                }
                ::-webkit-scrollbar{display: none;}
                html,body {
                    width: 100%;
                    height: 100%;
                    overflow-x: hidden;
                    overflow-y: auto;
                    background: var(--background);
                }
                .wrapper {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 100%;
                    height: 100%;
                    margin: 40px auto;
                }
                .wrapper > .container {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 35px;
                    align-items: center;
                    width: var(--width);
                    height: 100%;
                }
                .wrapper > .container > .info {
                    display: flex;
                    flex-direction: column;
                    width: 100%;
                    height: 100px;
                    padding: 4px 8px;
                    border-radius: 8px;
                    background: var(--background2);
                }
                .wrapper > .container > .info > .terminal {
                    display: flex;
                    gap: 8px;
                    font-family: cursive;
                    font-size: 14px;
                }
                .wrapper > .container > .info > .terminal > .title {
                    color: #BDFF52;
                }
                .wrapper > .container > .info > .terminal > .type {
                    color: #e0e4d9;
                }
                @keyframes blinking {
                    from {
                        opacity: 0;
                    }
                    to {
                        opacity: 1;
                    }
                }
                .wrapper > .container > .info > .terminal > .type::after {
                    position: relative;
                    content: " ";
                    left: 2px;
                    display: inline-block;
                    width: 3px;
                    height: 12px;
                    animation: blinking .3s alternate infinite;
                    background: #fff;
                }
                .wrapper > .container > .info > .wrong {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                    font-family: "Inconsolata", serif;
                    margin-top: 15px;
                }
                .wrapper > .container > .info > .wrong > .subtitle {
                    color: var(--primary);
                }
                .wrapper > .container > .info > .wrong > .title {
                    color: #fff;
                }
                .wrapper > .container > .content {
                    width: 100%;
                    border-radius: 8px;
                    overflow-y: auto;
                    overflow-x: hidden;
                    background: var(--background2);
                }
                .wrapper > .container > .content > .sign {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 100%;
                    height: 45px;
                    border-radius: 8px 0;
                    color: #C0BBBB;
                    letter-spacing: 2.5px;
                    font-family: "Inconsolata", serif;
                    text-transform: uppercase;
                    background: var(--background3);
                }
                .wrapper > .container > .content > main {
                    width: 100%;
                    overflow-y: auto;
                    overflow-x: hidden;
                    padding: 10px;
                }
                .wrapper > .container > .content > main > .ddd{
                    width: 100%;
                    height: auto;
                    padding: 12px;
                    border-radius: 6px;
                    background: #181818;
                }
                #jsonformatter {
                    color: #2b2b2b;
                    font-display: swap;
                    font-family: "Inconsolata", monospace;
                    font-size: 11.5px;
                    line-height: 16px;
                }

                .ddd[data-view="raw"] {
                    margin-top: 50px;
                }

                #jsonformatter > div {
                    padding: 5px 10px;
                }

                #jsonformatter span, #jsonformatter .string {
                    display: inline-block;
                }

                #jsonformatter .k {
                    color: #b16b2a;
                }

                #jsonformatter .string {
                    color: green;
                }

                #jsonformatter .number {
                    color: darkblue;
                }

                #jsonformatter .boolean.false {
                    color: red;
                }
                #jsonformatter .boolean.true {
                    color: green;
                }

                #jsonformatter .null {
                    color: magenta;
                }

                #jsonformatter .collapsible {
                    cursor: pointer;
                    display: inline-block;
                }

                #jsonformatter .content {
                    display: block;
                    margin-left: 21px;
                    padding-left: 15px;
                    border-left: 1px dotted #dedede;
                }

                #jsonformatter .content > div {
                    line-height: 18px;
                }

                #jsonformatter .ellipsis {
                    display: none;
                }

                #jsonformatter .arrow-right .ellipsis {
                    display: inline-block;
                }

                #jsonformatter .arrow-right svg, #jsonformatter .arrow-down svg {
                    float: left;
                }

                #jsonformatter .arrow-right path, #jsonformatter .arrow-down path {
                    fill: #e1e1e1;
                }

                #jsonformatter .placeholder-arrow {
                    display: inline-block;
                    width: 15px;
                    height: 15px;
                }

                #jsonformatter .arrow-down svg {
                    transform: rotate(90deg);
                }

                #jsonformatter .kvp {
                    display: block;
                }

                #jsonformatter .kvr {
                    display: flex;
                    flex-wrap: wrap;
                    align-items: flex-start;
                }

                #jsonformatter .k, #jsonformatter .c {
                    white-space: nowrap;
                }

                #jsonformatter .c {
                    margin-right: 5px;
                }

                #jsonformatter .v {
                    flex-grow: 1;
                    margin-left: 0;
                    word-wrap: break-word;
                }

                #jsonformatter button {
                    border-radius: 3px;
                    padding: 4px 8px 3px;
                    font-weight: 600;
                    font-family: "Inconsolata";
                    position: fixed;
                    right: 15px;
                    top: 13px;
                    border: none;
                    cursor: pointer;
                    transition: background-color 0.3s;
                    height: 28px;
                    width: 140px;
                }

                #jsonformatter button::before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                #jsonformatter button {
                    background-color: #4CAF50;
                    color: white;
                }

                #jsonformatter button::before {
                    content: "Formatted JSON";
                }

                #jsonformatter[data-view="raw"] button {
                    background-color: #ededed;
                    color: #6a6a6a;
                }

                #jsonformatter[data-view="raw"] button::before {
                    content: "Raw JSON";
                }

                #jsonformatter > div:nth-child(1) > span.collapsible > span.opening, span.closing {
                    display: inline-block;
                }

                #jsonformatter span.closing {
                    padding-left: 19px;
                }
                #jsonformatter span.empty {
                    padding-left: 17px;
                }

                #jsonformatter .content[style*="display: none"] + .closing {
                    padding-left: 0;
                }

                #jsonformatter .content[style*="display: none"] + .closing:before {
                    content: "...";
                    position: relative;
                }

                #jsonformatter .item-count::before {
                    content: "// " attr(count);
                    color: #888;
                    font-style: italic;
                    display: none;
                }

                #jsonformatter .content[style*="display: none"] + .closing + .item-count::before {
                    display: inline;
                    padding-left: 5px;
                }

                #jsonformatter .color-preview {
                    display: inline-block;
                    border-radius: 15px;
                    width: 10px;
                    height: 10px;
                    border: 1px solid #e1e1e1;
                    margin-right: 2px;
                    vertical-align: middle;
                }

                #jsonformatter .timestamp::after {
                    content: " /* " attr(data-timestamp-comment) " */ ";
                    color: #888;
                    font-style: italic;
                    margin-left: 3px;
                    margin-right: 3px;
                }

                #jsonformatter .jsonLintIcon {
                    cursor: pointer;
                    position: fixed;
                    right: 165px;
                    top: 13px;
                    fill: #6b6b6b;
                    transition: fill 0.3s;
                    border: 1px solid #cdcdcd;
                    border-radius: 3px;
                    padding: 3px 4px;
                }

                #jsonformatter[data-view="raw"] .jsonLintIcon {
                    fill: #6a6a6a;
                }

                #jsonformatter .jsonLintIcon:hover {
                    fill: #3a3a3a;
                }

                #jsonformatter .image-wrapper {
                    position: relative;
                    display: inline-block;
                    cursor: pointer;
                }

                #jsonformatter .image-wrapper img {
                    position: absolute;
                    top: 100%;
                    left: 50%;
                    transform: translateX(-50%);
                    max-width: 200px;
                    max-height: 200px;
                    padding: 8px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    z-index: 10;
                    opacity: 0;
                    pointer-events: none;
                    transition: opacity 0.3s ease;

                    background-color: #dadada;
                    background-image:
                        linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                        linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
                    background-size: 20px 20px;
                    background-position: 0 0, 10px 10px;
                }

                #jsonformatter .image-wrapper:hover img {
                    opacity: 1;
                }
                .closing_bracket{
                    padding: 0 !important;
                }
                @media (prefers-color-scheme: dark) {
                    #jsonformatter {
                        color: #e1e1e1;
                        background-color: #2b2b2b;
                    }

                    #jsonformatter .k {
                        color: #ffb86c;
                    }

                    #jsonformatter .string {
                        color: #50fa7b;
                    }

                    #jsonformatter .number {
                        color: #8be9fd;
                    }

                    #jsonformatter .boolean {
                        color: #ff79c6;
                    }

                    #jsonformatter .null {
                        color: #bd93f9;
                    }

                    #jsonformatter .content {
                        border-left: 1px dotted #555;
                    }

                    #jsonformatter .arrow-right path, #jsonformatter .arrow-down path {
                        fill: #888;
                    }

                    #jsonformatter[data-view="formatted"] button {
                        background-color: #6272a4;
                        color: #f8f8f2;
                    }

                    #jsonformatter[data-view="raw"] button {
                        background-color: #44475a;
                        color: #f8f8f2;
                    }

                    #jsonformatter .item-count::before {
                        color: #666;
                    }

                    #jsonformatter a {
                        color: #b8bdc7;
                    }
                }
                @media (max-width: 1040px) {
                    .wrapper > .container {
                        width: 95% !important;
                    }
                }
            </style>
        </head>
        <body>
            <div class="wrapper">
                <div class="container">
                    <div class="info">
                        <div class="terminal">
                            <span class="title">
                                ~ /dev/aqua/
                            </span>
                            <span class="type" id="typing"></span>
                        </div>
                        <div class="wrong">
                            <span class="subtitle">INFO</span>
                            <span class="title">Dump, Die, Debug</span>
                        </div>
                    </div>
                    <div class="content">
                        <div class="sign">
                            <span>Debug Content</span>
                        </div>
                        <main>
                            <div class="ddd"></div>
                        </main>
                    </div>
                </div>
            </div>
            <script>
                const unixTimestampPattern = /^\d{10}$/;

                function isJSON(str) {
                    try {
                        JSON.parse(str);
                        return true;
                    } catch (e) {
                        return false;
                    }
                }

                function isValidURL(str) {
                    try {
                        new URL(str);
                        return true;
                    } catch (_) {
                        return false;
                    }
                }

                function isImageUrl(str) {
                    const imagePattern = /\.(jpg|jpeg|png|gif|bmp|svg)(\?.*)?$/i;
                    return imagePattern.test(str);
                }

                function wrapImagePreview(str) {
                    if (isImageUrl(str)) {
                        return `<span class="image-wrapper"><a href="${str}" target="_blank">${str}</a></span>`;
                    }
                    return str;
                }

                function wrapURL(str) {
                    if (isValidURL(str)) {
                        return `<a href="${str}" target="_blank">${str}</a>`;
                    }
                    return str;
                }

                function wrapColor(str) {
                    const hexPattern = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
                    const rgbPattern = /^rgb\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*\)$/;
                    const rgbaPattern = /^rgba\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*(?:0(?:\.\d+)?|1(?:\.0+)?)\s*\)$/;

                    if (hexPattern.test(str) || rgbPattern.test(str) || rgbaPattern.test(str)) {
                        return `<span class="color-preview" style="background-color: ${str}"></span>${str}`;
                    }
                    return str;
                }

                function wrapTimestamp(str) {
                    if (unixTimestampPattern.test(str)) {
                        const date = new Date(Number(str) * 1000); // Convert to milliseconds
                        const formattedDate = date.toLocaleString("en-US", { month: "long", day: "numeric", year: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit", hour12: true, timeZoneName: "short" });
                        return `<span class="string">"${str}"</span><span class="timestamp" data-timestamp-comment="${formattedDate}"></span>`;
                    }
                    return `<span class="string">"${str}"</span>`;
                }

                function formatJSON(json) {
                    let parsedJSON = JSON.parse(json);
                    return traverseJSON(parsedJSON);
                }

                function traverseJSON(obj, isNested = false) {
                    if (Array.isArray(obj)) {
                        if (obj.length === 0) return isNested ? "" : `<span class="empty">[]</span>`;
                        let content = obj.map((item, index) => {
                            let itemContent = traverseJSON(item);
                            let comma = (index !== obj.length - 1) ? "," : "";
                            return `<div>${itemContent}${comma}</div>`;
                        }).join("");
                        return isNested ? content : `<span class="opening">[</span>${content}<span class="closing closing_bracket">]</span>`;
                    } else if (typeof obj === "object" && obj !== null) {
                        let keys = Object.keys(obj);
                        if (keys.length === 0) return isNested ? "" : `<span class="empty">{}</span>`;
                        let content = keys.map((key, index) => {
                            let value = obj[key];
                            let valueContent = traverseJSON(value, true);
                            let comma = (index !== keys.length - 1) ? "," : "";

                            if (Array.isArray(value)) {
                                return `<div><span class="arrow-down collapsible"><svg height="15" width="15" viewBox="0 0 48 48"><path d="M16 10v28l22-14z"/></svg></span><span class="k">"${key}"</span>: <span class="opening">[</span><span class="ellipsis"></span></span><div class="content array-wrapper">${valueContent}</div><span class="closing">]</span>${comma}<span class="item-count" count="${value.length} ${value.length === 1 ? "item" : "items"}"></span></div>`;
                            } else if (typeof value === "object" && value !== null) {
                                return `<div><span class="arrow-down collapsible"><svg height="15" width="15" viewBox="0 0 48 48"><path d="M16 10v28l22-14z"/></svg></span><span class="k">"${key}"</span>: <span class="opening">{</span><div class="content object-wrapper">${valueContent}</div><span class="closing">}</span>${comma}<span class="item-count" count="${Object.keys(value).length} ${Object.keys(value).length === 1 ? "item" : "items"}"></span></div>`;
                            } else {
                                return `<div class="kvp"><span class="kvr"><span class="placeholder-arrow"></span><span class="k">"${key}"</span><span class="c">:</span> <span class="v">${valueContent}${comma}</span></span></div>`;
                            }
                        }).join("");

                        if (isNested) {
                            return `${content}`;
                        } else {
                            return `<span class="arrow-down collapsible"><svg height="15" width="15" viewBox="0 0 48 48"><path d="M16 10v28l22-14z"/></svg><span class="opening">{</span><span class="ellipsis"></span></span><div class="content">${content}</div><span class="closing">}</span><span class="item-count" count="${keys.length} ${keys.length === 1 ? "item" : "items"}"></span>`;
                        }
                    } else {
                        if (typeof obj === "string") {
                            if (unixTimestampPattern.test(obj)) {
                                return wrapTimestamp(obj);
                            } else {
                                let wrappedStr = wrapURL(wrapColor(wrapImagePreview(obj)));
                                return `<span class="string">"${wrappedStr}"</span>`;
                            }
                        }
                        if (typeof obj === "number") return `<span class="number">${obj}</span>`;
                        if (typeof obj === "boolean") return `<span class="boolean ${obj ? "true" : "false"}">${obj}</span>`;
                        if (obj === null) return `<span class="null">null</span>`;
                    }
                }


                function formatJSONAndGiveItOut (element, json) {
                    if (isJSON(json)) {
                        const rawJSON = json;
                        element.id = "jsonformatter"
                        element.setAttribute("data-isjson", "yes");
                        function addCollapsibleListeners() {
                            document.querySelectorAll(".collapsible").forEach(collapsible => {
                                collapsible.addEventListener("click", function() {
                                    let content = this.nextElementSibling;
                                    while (content && !content.classList.contains("content")) {
                                        content = content.nextElementSibling;
                                    }
                                    if (content) {
                                        content.style.display = content.style.display === "none" ? "block" : "none";
                                        if (content.style.display === "none") {
                                            this.classList.replace("arrow-down", "arrow-right");
                                        } else {
                                            this.classList.replace("arrow-right", "arrow-down");
                                        }
                                    }
                                });
                            });
                        }

                        element.innerHTML = formatJSON(rawJSON);
                        addCollapsibleListeners();
                        element.setAttribute("data-view", "formatted");
                    }else{
                        element.innerHTML = json;
                    }
                }
                let words = [
                    "debugging information",
                    "getting data",
                    "aqua framework the best",
                    "error? don\'t worry",
                    "you are the best ;)",
                    "connecting to the server...(just kidding)",
                    "don\'t forget to refactor your code",
                    "php -r \"echo \'hello world\'\""
                ];
                let usedWords = [];
                let timer = 2200;
                async function blinking() {
                    let typingBlock = document.querySelector("#typing");
                    let random = typingBlock.textContent;
                    let newRandom = random;
                    let timerStep = Math.floor(timer / random.length);
                    let count = 0;
                    let interval = await setInterval(() => {
                        if (count > timer) {
                            clearInterval(interval);
                            typing();
                        }
                        count += timerStep;
                        typingBlock.innerText = newRandom;
                        newRandom = newRandom.slice(0, newRandom.length - 1)
                    }, timerStep);
                }
                async function typing() {
                    let seed = Math.floor(Math.random() * words.length);
                    while (true) {
                        seed = Math.floor(Math.random() * words.length);
                        if (!usedWords.includes(seed)) break;
                    }
                    if (usedWords.length >= words.length - 1) usedWords = [];
                    let random = words[seed];
                    usedWords.push(seed);
                    let newRandom = "";
                    let typingBlock = document.querySelector("#typing");
                    typingBlock.innerText = ``;
                    let timerStep = Math.floor(timer / random.length);
                    let count = 0;
                    let i = 0;
                    let interval = await setInterval(() => {
                        if (count > timer){
                            clearInterval(interval);
                            blinking();
                        }
                        count += timerStep;
                        if (random[i] !== undefined)
                            newRandom += random[i];
                        typingBlock.innerText = newRandom;
                        i++;
                    }, timerStep);
                }
                typing();
                formatJSONAndGiveItOut(document.querySelector("main .ddd"), `' . $debugString . '`);
            </script>
        </body>
        </html>');
}