</main>
<?php $u = me(); ?>
<div class="floating-chatbot" id="floatingChatbot" aria-live="polite">
    <button type="button" class="chatbot-toggle" id="chatbotToggle" aria-label="Open AI assistant">AI</button>
    <div class="chatbot-panel" id="chatbotPanel">
        <div class="chatbot-heading">
            <span>AI Assistant</span>
            <button type="button" id="chatbotClose" aria-label="Close AI assistant">×</button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="chatbot-message bot">Hi! I can help with courses, assignments, grades, and LMS basics.</div>
        </div>
        <form id="chatbotForm" class="chatbot-form">
            <input type="text" id="chatbotInput" placeholder="Ask a quick question..." autocomplete="off">
            <button type="submit" class="btn small">Send</button>
        </form>
    </div>
</div>
<script>
(function () {
    const panel = document.getElementById('chatbotPanel');
    const toggle = document.getElementById('chatbotToggle');
    const closeBtn = document.getElementById('chatbotClose');
    const form = document.getElementById('chatbotForm');
    const input = document.getElementById('chatbotInput');
    const messages = document.getElementById('chatbotMessages');

    function addMessage(text, from) {
        const item = document.createElement('div');
        item.className = 'chatbot-message ' + from;
        item.textContent = text;
        messages.appendChild(item);
        messages.scrollTop = messages.scrollHeight;
    }

    function answerQuestion(question) {
        const q = question.toLowerCase();

        if (!q || q.includes('hi') || q.includes('hello')) return 'Hi! I can help with courses, assignments, login, grades, and navigation.';
        if (q.includes('enroll') || q.includes('join')) return 'Open Courses, pick the course, and click Enroll to join it.';
        if (q.includes('assignment') || q.includes('submit')) return 'Go to the course page, open the assignment, and use the submission box to send your answer.';
        if (q.includes('grade') || q.includes('mark') || q.includes('result')) return 'Check your grades from the Dashboard under My results.';
        if (q.includes('login') || q.includes('account')) return 'Use the Log in page or Create account page.';
        if (q.includes('teacher') || q.includes('student') || q.includes('admin')) return 'EduNest supports Admin, Teacher, and Student roles.';
        if (q.includes('course')) return 'Teachers create courses, students enroll, and course materials live inside each course page.';
        return 'I can help with course access, assignment submission, enrollments, grades, and platform basics.';
    }

    toggle.addEventListener('click', function () {
        panel.classList.toggle('open');
        if (panel.classList.contains('open')) {
            input.focus();
        }
    });

    closeBtn.addEventListener('click', function () {
        panel.classList.remove('open');
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const question = input.value.trim();
        if (!question) return;
        addMessage(question, 'user');
        input.value = '';
        setTimeout(function () {
            addMessage(answerQuestion(question), 'bot');
        }, 200);
    });
})();
</script>
<footer>EduNest</footer></body></html>
