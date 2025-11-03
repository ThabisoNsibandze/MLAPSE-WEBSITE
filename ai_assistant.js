document.addEventListener('DOMContentLoaded', () => {
    const aiAssistantButton = document.getElementById('aiAssistantButton');
    const aiAssistantChatWindow = document.getElementById('aiAssistantChatWindow');
    const closeChatButton = document.getElementById('closeChatButton');
    const chatInput = document.getElementById('chatInput');
    const sendMessageButton = document.getElementById('sendMessageButton');
    const chatBody = document.getElementById('chatBody');

    // Create quick reply container
    const quickReplyContainer = document.createElement('div');
    quickReplyContainer.classList.add('quick-reply-container');
    chatBody.parentNode.insertBefore(quickReplyContainer, chatBody.nextSibling);

    // Extract Mission and Vision
    const missionElement = document.querySelector('#about .about-section:first-of-type .col-3 p');
    const visionElements = document.querySelectorAll('#about .about-section:last-of-type .col-3 p');
    const mission = missionElement
        ? missionElement.textContent.trim()
        : "MLAPSE empowers and uplifts people living with disabilities in Eswatini through advocacy, education, and technology-driven accessibility.";
    const vision = visionElements.length > 0
        ? Array.from(visionElements).map(p => p.textContent.trim()).join('\n')
        : "Empowering communities through sustainable development, education, and economic empowerment.";

    // Typing indicator
    const typingIndicator = document.createElement('div');
    typingIndicator.classList.add('typing-indicator');
    typingIndicator.innerHTML = `<span>MLAPSE Assistant is typing</span><span class="dot"></span><span class="dot"></span><span class="dot"></span>`;
    typingIndicator.style.display = 'none';
    chatBody.appendChild(typingIndicator);

    // Toggle chat window
    aiAssistantButton.addEventListener('click', () => {
        aiAssistantChatWindow.classList.toggle('active');
    });
    closeChatButton.addEventListener('click', () => {
        aiAssistantChatWindow.classList.remove('active');
    });

    // Add message function
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', `${sender}-message`);
        messageDiv.textContent = text;
        chatBody.insertBefore(messageDiv, typingIndicator);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // Typing animation controls
    function showTyping() {
        typingIndicator.style.display = 'flex';
        chatBody.scrollTop = chatBody.scrollHeight;
    }
    function hideTyping() {
        typingIndicator.style.display = 'none';
    }

    // Quick reply buttons
    const quickReplies = [
        { label: "Mission", text: "What is your mission?" },
        { label: "Vision", text: "What is your vision?" },
        { label: "Donate", text: "How can I donate?" },
        { label: "Volunteer", text: "How can I volunteer?" },
        { label: "Contact", text: "How can I contact you?" },
        { label: "About MLAPSE", text: "What is MLAPSE?" }
    ];

    quickReplies.forEach(reply => {
        const btn = document.createElement('button');
        btn.textContent = reply.label;
        btn.classList.add('quick-reply');
        btn.addEventListener('click', () => {
            chatInput.value = reply.text;
            sendMessage();
        });
        quickReplyContainer.appendChild(btn);
    });

    // Main chatbot logic
    function sendMessage() {
        const userText = chatInput.value.trim();
        if (userText === '') return;

        addMessage(userText, 'user');
        chatInput.value = '';

        const text = userText.toLowerCase();
        let botResponse = null;

        // General replies
        if (/(hi|hello|hey|good morning|good afternoon|good evening)\b/.test(text)) {
            botResponse = "Hello there 👋! Welcome to MLAPSE — how can I help you today?";
        } else if (/how are you/.test(text)) {
            botResponse = "I'm great, thank you! I'm here to assist you with MLAPSE-related questions.";
        } else if (/(thank you|thanks)/.test(text)) {
            botResponse = "You're most welcome! 😊";
        } else if (/(who are you|what can you do|introduce yourself)/.test(text)) {
            botResponse = "I'm the MLAPSE Virtual Assistant 🤖. I can help you with our mission, vision, volunteering, and donation details.";
        }

        // MLAPSE-specific
        else if (/mission/.test(text)) {
            botResponse = `Our Mission:\n${mission}`;
        } else if (/vision/.test(text)) {
            botResponse = `Our Vision:\n${vision}`;
        } else if (/what is mlapse|about mlapse|who is mlapse/.test(text)) {
            botResponse = "MLAPSE (Mavalela / Likhetseni Association for Persons Living with Disability in Eswatini) is a registered NGO that advocates for, empowers, and uplifts people living with disabilities in Eswatini.";
        } else if (/what do you do|activities|projects|programs/.test(text)) {
            botResponse = "We focus on building inclusive communities through advocacy, education, healthcare, and technology-driven accessibility initiatives.";
        } else if (/volunteer|join/i.test(text)) {
            botResponse = "You can join MLAPSE as a volunteer by clicking 'Join As Volunteer' on our site or emailing mlapse@gmail.com 💙";
        } else if (/donate|help|support/i.test(text)) {
            botResponse = "Thank you for your kindness! 💙 You can support MLAPSE by clicking 'Donate Now' on our website. Every contribution helps make a big difference.";
        } else if (/contact|address|location|where are you/i.test(text)) {
            botResponse = "📍 P.O Box 128, Big-Bend, Lunkuntu, Lubombo, Eswatini\n📞 +268 79868749\n📧 mlapse@gmail.com";
        } else if (/founder|co[- ]?founder|who started/i.test(text)) {
            botResponse = "MLAPSE was co-founded by Mr. Mphephiseni, with volunteers including Thabiso Nsibandze and others passionate about empowering communities.";
        } else if (/social|follow|facebook|instagram|pages/i.test(text)) {
            botResponse = "Follow MLAPSE on our social media pages (under 'Follow Us') to stay updated on community work and donation drives.";
        } else {
            botResponse = "I'm sorry, I can only answer questions related to MLAPSE. Please try asking about our mission, vision, volunteering, or donations. 💬";
        }

        // Typing simulation
        showTyping();
        setTimeout(() => {
            hideTyping();
            addMessage(botResponse, 'bot');
        }, 1200);
    }

    sendMessageButton.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') sendMessage();
    });
});
