<?php
function generateEmailVerificationToken(int $byte = 32, string $ttlSpec = '+48 hours') : array
{
    $raw = random_bytes($byte);
    $token = bin2hex($raw);
    $expiresAt = new DateTimeImmutable($ttlSpec);
    return [$token, $expiresAt];
}