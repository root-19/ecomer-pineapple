
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .chat-box {
            height: 400px;
            overflow-y: auto;
        }
    </style>
</head>

<div class="max-w-md w-full shadow-lg rounded-lg">
        <div class="p-4 border-b border-gray-200 flex items-center justify-center">
            <i class="fas fa-robot text-3xl text-blue-600 mr-2"></i>
            <h2 class="text-2xl font-bold text-center text-blue-600">Product Chatbot</h2>
        </div>
        <div class="chat-box p-4 mb-4 border border-gray-300 rounded-lg bg-gray-50">
            <div id="chat-output" class="space-y-2"></div>
        </div>
        <div class="p-4">
            <div class="flex">
                <input type="text" id="user-input" class="border border-gray-300 rounded-l-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ask about our products...">
                <button id="send-btn" class="bg-blue-500 text-white rounded-r-lg p-2 hover:bg-blue-600 transition duration-300">Send</button>
            </div>
        </div>
    </div>
    <script>

        // Sample product data
const products = [
    { name: "Laptop", price: "$999", description: "High-performance laptop for work and play." },
    { name: "Smartphone", price: "$699", description: "Latest model with advanced features." },
    { name: "Headphones", price: "$199", description: "Noise-cancelling headphones for immersive sound." },
    { name: "Smartwatch", price: "$299", description: "Stay connected on the go." },
];

// Add event listener for the send button
document.getElementById("send-btn").addEventListener("click", function() {
    const userInput = document.getElementById("user-input").value.toLowerCase();
    addChatMessage("You: " + userInput);

    const response = getResponse(userInput);
    addChatMessage("Bot: " + response);

    document.getElementById("user-input").value = "";
});

// Function to add chat messages
function addChatMessage(message) {
    const chatOutput = document.getElementById("chat-output");
    const messageElement = document.createElement("div");
    messageElement.textContent = message;
    chatOutput.appendChild(messageElement);
    chatOutput.scrollTop = chatOutput.scrollHeight; // Scroll to the bottom
}

// Function to get responses based on user input
function getResponse(userInput) {
    // Define keywords for specific product inquiries
    const productKeywords = {
        laptop: `${products[0].name}: ${products[0].price} - ${products[0].description}`,
        smartphone: `${products[1].name}: ${products[1].price} - ${products[1].description}`,
        headphones: `${products[2].name}: ${products[2].price} - ${products[2].description}`,
        smartwatch: `${products[3].name}: ${products[3].price} - ${products[3].description}`,
    };

    // Check for specific product inquiries first
    for (const keyword in productKeywords) {
        if (userInput.includes(keyword)) {
            return productKeywords[keyword];
        }
    }

    // If no specific product matched, check for a general inquiry
    if (userInput.includes("products") || userInput.includes("available")) {
        return "We have the following products available: Laptop, Smartphone, Headphones, Smartwatch.";
    }

    // Fallback response for unrecognized queries
    return "I'm sorry, I don't have information about that product. Please ask about our available products.";
}

    </script>

