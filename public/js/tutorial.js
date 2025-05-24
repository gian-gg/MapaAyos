let currentStep = 1;
const totalSteps = 12;

function updateStep() {
  const img = document.getElementById("tutorial-image");
  const backBtn = document.getElementById("back-btn");
  const nextBtn = document.getElementById("next-btn");
  const finishBtn = document.getElementById("finish-btn");
  const text = document.getElementById("tutorial-text");

  const messages = [
    "",
    "Welcome to MapaAyos!",
    "Let's start the Tutorial!",
    "First off, This is the Map Page",
    "Select Baranggay button, shows the different baranggays as well as locking your perspective on that chosen baranggay",
    "When selected it shows you a ton of information about the baranggay",
    "Active report button, it toggles what reports to show on the map",
    "This is the Baranggay Page. This is where you will find information, contacts, and recent reports within the baranggay",
    "Each baranggay has its own page full of information!",
    "To report, FIRST select a baranggay, SECOND click anywhere in the map within the baranggay borders, THIRD Click report",
    "A report form will pop-up and do the necessary fill-outs to submit a report!",
    "Your reports will show on the toggle map and in the recent reports",
    "That's pretty much it. Your now good to go!",
  ];

  img.classList.add("fade-out");
  text.classList.add("text-fade-out");

  setTimeout(() => {
    img.src = `/public/img/step${currentStep}.png`;
    text.innerText = messages[currentStep] || "";

    img.onload = () => {
      img.classList.remove("fade-out");
      img.classList.add("fade-in");

      setTimeout(() => img.classList.remove("fade-in"), 300);
    };

    text.classList.remove("text-fade-out");
    text.classList.add("text-fade-in");

    setTimeout(() => text.classList.remove("text-fade-in"), 300);

    backBtn.style.display = currentStep === 1 ? "none" : "inline-block";
    nextBtn.style.display =
      currentStep === totalSteps ? "none" : "inline-block";
    finishBtn.style.display =
      currentStep === totalSteps ? "inline-block" : "none";
  }, 250);
}

function nextStep() {
  if (currentStep < totalSteps) {
    currentStep++;
    updateStep();
  }
}

function prevStep() {
  if (currentStep > 1) {
    currentStep--;
    updateStep();
  }
}

updateStep();
