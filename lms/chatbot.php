<?php
require 'config.php';
$u = need();

function fallback_ai_reply($message, $user) {
    $text = strtolower(trim((string) $message));

    if ($text === '' || preg_match('/^(hi|hello|hey|good morning|good evening)/', $text)) {
        return 'Hi! I can help with your courses, assignments, grading, and how to use EduNest.';
    }
    if (str_contains($text, 'enroll') || str_contains($text, 'join')) {
        return 'To enroll in a course: open the Courses page, find a course you want, and click the Enroll button. Once enrolled, you can access materials and assignments.';
    }
    if (str_contains($text, 'assignment') || str_contains($text, 'submit')) {
        return 'Open the course, go to Assignments, choose the work, and submit your answer in the text box. If you need to edit a submission, submit again and the latest version will be saved.';
    }
    if (str_contains($text, 'grade') || str_contains($text, 'result') || str_contains($text, 'mark')) {
        return 'Students can check graded work from the Dashboard under My results. Teachers can grade submissions inside the course page.';
    }
    if (str_contains($text, 'course') || str_contains($text, 'material') || str_contains($text, 'lesson')) {
        return 'Course materials are stored inside each course page. Teachers can add learning files and announcements, and students can open them from there.';
    }
    if (str_contains($text, 'teacher') || str_contains($text, 'student') || str_contains($text, 'admin')) {
        return 'This LMS supports three roles: Admin, Teacher, and Student. Admin manages users, Teacher creates courses, and Student enrolls and submits work.';
    }
    if (str_contains($text, 'login') || str_contains($text, 'account')) {
        return 'Use the Log in page to sign in with your registered email and password. New users can create an account from the Create account page.';
    }
    if (str_contains($text, 'help') || str_contains($text, 'what can you do')) {
        return 'I can explain how to use EduNest, find course steps, answer assignment questions, explain grading, and guide students and teachers through the platform.';
    }

    return 'I can help with the LMS workflow: course access, enrollments, assignments, submissions, grades, and platform navigation. Ask me a specific question and I will guide you.';
}

function generate_ai_reply($conversation, $user) {
    $latestMessage = '';
    foreach (array_reverse($conversation) as $entry) {
        if (($entry['role'] ?? '') === 'user') {
            $latestMessage = (string) ($entry['content'] ?? '');
            break;
        }
    }

    $apiKey = getenv('OPENAI_API_KEY') ?: ($_ENV['OPENAI_API_KEY'] ?? '');
    if ($apiKey !== '' && function_exists('curl_init')) {
        $prompt = [
            ['role' => 'system', 'content' => "You are the EduNest AI course assistant for a PHP learning management system. Help students and teachers with course access, enrollments, assignments, grading, and platform navigation. Keep answers short, useful, and practical."],
        ];

        foreach ($conversation as $entry) {
            if (isset($entry['role'], $entry['content'])) {
                $prompt[] = ['role' => $entry['role'], 'content' => (string) $entry['content']];
            }
        }

        $payload = [
            'model' => getenv('OPENAI_MODEL') ?: 'gpt-4o-mini',
            'messages' => $prompt,
            'temperature' => 0.7,
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        if ($response !== false) {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $data = json_decode($response, true);
            if ($httpCode >= 200 && $httpCode < 300 && !empty($data['choices'][0]['message']['content'])) {
                curl_close($ch);
                return trim((string) $data['choices'][0]['message']['content']);
            }
        }
        curl_close($ch);
    }

    return fallback_ai_reply($latestMessage, $user);
}

if (isset($_GET['reset'])) {
    unset($_SESSION['chatbot_history']);
    header('Location: chatbot.php');
    exit;
}

$history = $_SESSION['chatbot_history'] ?? [
    ['role' => 'assistant', 'content' => 'Hello! I am your EduNest AI assistant. Ask me about courses, assignments, enrollment, or how to use the platform.'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim((string) ($_POST['message'] ?? ''));
    if ($message !== '') {
        $history[] = ['role' => 'user', 'content' => $message];
        $history[] = ['role' => 'assistant', 'content' => generate_ai_reply($history, $u)];
        $_SESSION['chatbot_history'] = $history;
    }
    header('Location: chatbot.php');
    exit;
}

$title = 'AI Chatbot';
include 'header.php';
?>
<section class="card chatbot-shell">
    <div class="chat-header">
        <div>
            <h2>AI Course Assistant</h2>
            <p class="muted">Ask about courses, submissions, grading, or LMS navigation.</p>
        </div>
        <a class="btn ghost" href="chatbot.php?reset=1">Clear chat</a>
    </div>

    <div class="chat-messages">
        <?php foreach ($history as $msg): ?>
            <div class="msg <?= ($msg['role'] ?? 'assistant') === 'user' ? 'user' : 'assistant' ?>">
                <div class="bubble"><?= nl2br(e($msg['content'] ?? '')) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <form class="chat-form" method="post">
        <?= f() ?>
        <textarea name="message" rows="3" placeholder="Ask the chatbot about a course, assignment, grade, or how to use EduNest..." required></textarea>
        <div class="chat-actions">
            <button class="btn" type="submit">Send message</button>
        </div>
    </form>

    <div class="quick-prompts">
        <button class="chip" type="button" data-text="How do I enroll in a course?">How do I enroll?</button>
        <button class="chip" type="button" data-text="How do I submit an assignment?">Submit assignment</button>
        <button class="chip" type="button" data-text="Where do I check my grades?">Check grades</button>
        <button class="chip" type="button" data-text="What can the AI chatbot help with?">What can you do?</button>
    </div>
</section>

<script>
document.querySelectorAll('.chip').forEach(function(button) {
    button.addEventListener('click', function() {
        var textarea = document.querySelector('.chat-form textarea');
        if (textarea) {
            textarea.value = this.dataset.text || '';
            textarea.focus();
        }
    });
});
</script>
<?php include 'footer.php'; ?>
