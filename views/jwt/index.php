<?php
/* @var $this yii\web\View */

$now = new \DateTime();
$time = $now->getTimestamp();
$jwt = Yii::$app->jwt;
$signer = $jwt->getSigner('HS256');
$key = $jwt->getKey();

/* @var \Lcobucci\JWT\Token\Plain $token */
$token = Yii::$app->jwt->getBuilder()
    ->issuedBy('http://example.com') // Configures the issuer (iss claim)
    ->permittedFor('http://example.org') // Configures the audience (aud claim)
    ->identifiedBy('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
    ->issuedAt($time) // Configures the time that the token was issue (iat claim)
    ->canOnlyBeUsedAfter($time + 60) // Configures the time that the token can be used (nbf claim)
    ->expiresAt($time + 3600) // Configures the expiration time of the token (exp claim)
    ->withClaim('uid', 1) // Configures a new claim, called "uid"
    ->withClaim('email',"thomas.alfa.edison@gmail.com")
    ->getToken($signer,$key); // Retrieves the generated token


//$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODA3NFwvc3NvLWxhcmF2ZWxcL3B1YmxpY1wvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTYyMDY5MDcxOCwiZXhwIjoxNjIwNjk0MzE4LCJuYmYiOjE2MjA2OTA3MTgsImp0aSI6IjFKTjM3QjdjcHVSOEFXOHoiLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.5kTvzoyJ4jJz0roO5WoWNBpD2K1dNkCPMvLvRBE7ba8";
$tokenLara = Yii::$app->jwt->getParser()->parse((string) $token); // Parses from a string
$tokenLara->getHeaders(); // Retrieves the token header
$tokenLara->getClaims(); // Retrieves the token claims

?>


<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Tes JWT</h3>
    </div>
    <div class="box-body">
        <?= $token; ?>
        <div>
            <?= $tokenLara->getHeader('jti'); // will print "4f1g23a12aa" ?><br/>
            <?= $tokenLara->getClaim('iss'); // will print "http://example.com" ?><br/>
            <?= $tokenLara->getClaim('uid'); // will print "1" ?>
            <?= $tokenLara->getClaim('email'); // will print "1" ?>
        </div>
        <div>
            <?= var_dump($token->verify($signer, 'sss')); ?>
        </div>
    </div>
</div>
