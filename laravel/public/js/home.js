const tasks = [
{
    name: "睡眠",
    start: "00:00",
    duration: 420,
    color: "#5e5ce6"
},
{
    name: "学校",
    start: "09:00",
    duration: 360,
    color: "#30d158"
},
{
    name: "SNS",
    start: "16:00",
    duration: 120,
    color: "#2db4ff"
},
{
    name: "勉強",
    start: "19:00",
    duration: 180,
    color: "#ff9500"
}
];

const goals = [
"朝の運動をする",
"課題を終わらせる",
"SNSを1時間以内",
"読書を30分する",
"筋トレをする"
];

function timeToMinutes(time){
const [hour, minute] = time.split(":").map(Number);
return hour * 60 + minute;
}

function minutesToTime(minutes){
let hour = Math.floor(minutes / 60);
let minute = minutes % 60;

if(hour >= 24){
    hour -= 24;
}

return `${String(hour).padStart(2,"0")}:${String(minute).padStart(2,"0")}`;
}

function createChart(){
const chart = document.getElementById("circleChart");

chart.innerHTML = "";

let gradients = [];
let currentPercent = 0;

tasks.forEach(task => {
    const startMinutes = timeToMinutes(task.start);
    const endMinutes = startMinutes + task.duration;

    const startPercent = (startMinutes / 1440) * 100;
    const endPercent = (endMinutes / 1440) * 100;

    if(startPercent > currentPercent){
    gradients.push(
        `#d8e8da ${currentPercent}% ${startPercent}%`
    );
    }

    gradients.push(
    `${task.color} ${startPercent}% ${endPercent}%`
    );

    currentPercent = endPercent;

    const middleMinutes = startMinutes + (task.duration / 2);

    const angle = (middleMinutes / 1440) * 360 - 90;

    const radius = 105;

    const x = 125 + radius * Math.cos(angle * Math.PI / 180);

    const y = 125 + radius * Math.sin(angle * Math.PI / 180);

    const label = document.createElement("div");

    label.className = "chart-label";

    label.style.left = `${x}px`;
    label.style.top = `${y}px`;

    label.innerHTML = `
    ${task.name}<br>
    ${task.start}〜${minutesToTime(endMinutes)}
    `;

    chart.appendChild(label);
});

if(currentPercent < 100){
    gradients.push(
    `#d8e8da ${currentPercent}% 100%`
    );
}

chart.style.background =
    `conic-gradient(${gradients.join(",")})`;
}

createChart();

function createGoals(){
const goalList = document.getElementById("goalList");

goalList.innerHTML = "";

goals.slice(0, 5).forEach(goal => {
    goalList.innerHTML += `
    <div class="goal-item">

        <div class="goal-text">
        ${goal}
        </div>

        <button class="cancel-btn" onclick="cancelGoal(this)">
        取消
        </button>

        <button class="circle-btn" onclick="doneGoal(this)">
        ○
        </button>

        <button class="delete-btn" onclick="openReasonModal(this)">
        ×
        </button>

    </div>
    `;
});
}

createGoals();

let isGroup = false;

function changeGoal(){
const title = document.getElementById("goalTitle");

if(isGroup === false){
    title.textContent = "本日のグループ目標一覧";
    isGroup = true;
}else{
    title.textContent = "本日の目標一覧";
    isGroup = false;
}
}

function doneGoal(button){
const item = button.parentElement;

const cancelBtn = item.querySelector(".cancel-btn");
const circleBtn = item.querySelector(".circle-btn");
const deleteBtn = item.querySelector(".delete-btn");

circleBtn.style.display = "none";
deleteBtn.style.display = "none";
cancelBtn.style.display = "block";
}

let currentItem = null;

function openReasonModal(button){
currentItem = button.parentElement;

document.getElementById("overlay").style.display = "flex";

document.getElementById("circleChart")
    .classList.add("hide-labels");
}

function closeReasonModal(){
document.getElementById("overlay").style.display = "none";

const checked =
    document.querySelector('input[name="reason"]:checked');

if(checked){
    checked.checked = false;
}
}

function registerReason(){
const selected =
    document.querySelector('input[name="reason"]:checked');

if(!selected){
    alert("理由を選択してください");
    return;
}

const cancelBtn =
    currentItem.querySelector(".cancel-btn");

const circleBtn =
    currentItem.querySelector(".circle-btn");

const deleteBtn =
    currentItem.querySelector(".delete-btn");

circleBtn.style.display = "none";
deleteBtn.style.display = "none";
cancelBtn.style.display = "block";

document.getElementById("overlay").style.display = "none";

document.getElementById("circleChart")
    .classList.remove("hide-labels");

selected.checked = false;
}

function cancelGoal(button){
const item = button.parentElement;

const cancelBtn = item.querySelector(".cancel-btn");
const circleBtn = item.querySelector(".circle-btn");
const deleteBtn = item.querySelector(".delete-btn");

cancelBtn.style.display = "none";
circleBtn.style.display = "block";
deleteBtn.style.display = "flex";

document.getElementById("circleChart")
    .classList.remove("hide-labels");
}

const menuButtons =
document.querySelectorAll(".menu-btn");

menuButtons.forEach(button => {
button.addEventListener("click", () => {

    menuButtons.forEach(btn => {
    btn.classList.remove("active-btn");
    });

    button.classList.add("active-btn");
});
});