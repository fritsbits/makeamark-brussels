<p>Hi {{ $applicantName }},</p>

<p>We’ve received your {{ \App\Support\Audiences::find($audience)->noun }} application for Make a Mark Brussels {{ $editionYear }}.</p>

<p>The organising team will review it. We’ll be in touch about the next steps.</p>

<p>Thank you,<br>The Make a Mark Brussels team</p>
