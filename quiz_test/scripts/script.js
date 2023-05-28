const correctAnwser = Number(document.getElementById("secret").value);
const answerButtons = document.querySelectorAll(".answer-button");
const nextQuestionButton = document.getElementById("nextQuestionButton");
let playerScoreElement = document.getElementById("score");
let playerScore = Number(playerScoreElement.value);
let timeLeftElement = document.getElementById("timeLeft");

function checkAnswer(answerId, correctAnwser) {
    return answerId === correctAnwser;
}

function disableButtons(buttons) {
    buttons.forEach(button => {
        button.disabled = true;
    });
}

let timeLeft = 15;

const questionTimer = setInterval(updateCountdown, 1000);

function updateCountdown() {
    if (timeLeftElement.innerHTML === '1') {
        clearInterval(questionTimer);
        answerButtons[correctAnwser].classList.add("correct-answer");
        disableButtons(answerButtons);
        nextQuestionButton.disabled = false;
    }
    timeLeftElement.innerHTML = `${timeLeft}`;
    timeLeft--;
}

answerButtons[correctAnwser].addEventListener("click", () => {
    clearInterval(questionTimer);
    answerButtons[correctAnwser].classList.add("correct-answer");
    disableButtons(answerButtons);
    playerScore++;
    playerScoreElement.value = playerScore;
    nextQuestionButton.disabled = false;
})

for (let i = 0; i < 4; i++) {
    if (i !== correctAnwser) {
        answerButtons[i].addEventListener("click", () => {
            clearInterval(questionTimer);
            answerButtons[i].classList.add("wrong-answer");
            answerButtons[correctAnwser].classList.add("correct-answer");
            disableButtons(answerButtons);
            nextQuestionButton.disabled = false;
        })
    }
}