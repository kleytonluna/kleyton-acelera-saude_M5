function sendMessage() {
    var userInput = document.getElementById("user-input").value;
    var chatBox = document.getElementById("chat-box");
    
    // Display user message
    var userMessage = document.createElement("div");
    userMessage.className = "chat-message";
    userMessage.innerText = userInput;
    chatBox.appendChild(userMessage);
  
    // Respond to user message
    var botResponse = document.createElement("div");
    botResponse.className = "chat-message";
    botResponse.innerText = getBotResponse(userInput);
    chatBox.appendChild(botResponse);
  
    // Clear input field
    document.getElementById("user-input").value = "";
  }
  
  function getBotResponse(userInput) {
    // Simple responses based on user input
    if (userInput.toLowerCase().includes("bom dia")) {
      return "Como que posso te ajudar?";
    } else if (userInput.toLowerCase().includes("gostaria de informações")) {
      return "1 Para consulta; 2 Para informções; 3 Falar com atendente. ";
    } else if (userInput.toLowerCase().includes("1")) {
      return "Aguarde entraremos em contato!";
    } else if (userInput.toLowerCase().includes("2")) {
      return "Aguarde entraremos em contato!";
    } else if (userInput.toLowerCase().includes("3")) {
      return "Aguarde entraremos em contato!";
    } else {
      return "Desculpe, não entendi. Por favor, tente outra pergunta.";
    }
  }
  