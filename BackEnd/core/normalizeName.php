<?php
declare(strict_types=1);
class normalizeName {

    public function __construct(private ?string $name = null) {}
    public function normalize():string {
        $lCaseName = strtolower($this->name);

        $lCaseName = preg_replace_callback("/(?:^|[\s'\-\.])(\p{L})/u",function($matches){
            return strtoupper($matches[0]);
        },$lCaseName);
        return $lCaseName;
    }
    public function isValid(): bool {
        if (is_null($this->name)) return false;
        // Reject if anything other than letters, spaces, hyphens, or apostrophes
        return (bool)preg_match("/^[\p{L}\s'\-\.]+$/u", $this->name);
    }
    public function validatedNormalize(): string {
        if (!$this->isValid()) {
            throw new InvalidArgumentException("Invalid characters in name: {$this->name}");
        }
        return $this->normalize();
    }
}