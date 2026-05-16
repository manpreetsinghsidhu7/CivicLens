<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 20px; }
        .card { background: #fff; border-radius: 8px; padding: 30px; max-width: 500px; margin: 0 auto; }
        h2 { color: #1a56db; margin-top: 0; }
        .info { background: #f8fafc; border-radius: 6px; padding: 15px; margin: 15px 0; font-size: 14px; }
        .info div { margin-bottom: 8px; }
        .footer { text-align: center; color: #9ca3af; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Feedback Submitted Successfully!</h2>
        <p>Dear {{ $feedback->user->name ?? 'User' }},</p>
        <p>Your feedback has been submitted successfully on CivicLens. Here are the details:</p>
        <div class="info">
            <div><strong>Article:</strong> {{ $feedback->news->title ?? 'N/A' }}</div>
            <div><strong>Trust Score:</strong> {{ $feedback->trust_score }}/5</div>
            <div><strong>Clarity Score:</strong> {{ $feedback->clarity_score }}/5</div>
            <div><strong>Bias Level:</strong> {{ $feedback->bias_level }}</div>
            <div><strong>Sentiment:</strong> {{ $feedback->sentiment }}</div>
        </div>
        <p>Thank you for contributing to transparent governance feedback.</p>
        <p>— CivicLens Team</p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} CivicLens. All rights reserved.
    </div>
</body>
</html>
