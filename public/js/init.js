console.log("Hello Aqua Framework! ;)");
let words = [
	"github.com/codingweb123/aqua-framework-beta",
	"my first attemp to do framework",
	"aqua framework is the best",
	"error? don't worry, you can fix it",
	"you are the best ;)",
	"connecting to the server...(just kidding)",
	"don't forget to refactor your code",
	"php -r \"echo 'hello world'\"",
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
		newRandom = newRandom.slice(0, newRandom.length - 1);
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
		if (count > timer) {
			clearInterval(interval);
			blinking();
		}
		count += timerStep;
		if (random[i] !== undefined) newRandom += random[i];
		typingBlock.innerText = newRandom;
		i++;
	}, timerStep);
}
typing();
