<?php

namespace App\Services;

class QrisService
{
    /**
     * Generate string QRIS Dinamis dari QRIS Statis.
     *
     * @param string $staticQr String QRIS statis merchant
     * @param int|float $amount Nominal pembayaran
     * @return string
     */
    public function generate(string $staticQr, int|float $amount): string
    {
        // 1. Hapus 8 karakter CRC16 di ujung string (format: 6304XXXX)
        $baseQr = substr($staticQr, 0, -8);

        // 2. Hapus Tag 54 (Transaction Amount) jika sebelumnya sudah ada
        $baseQr = preg_replace('/54\d{2}\d+/', '', $baseQr);

        // 3. Susun Tag 54 (Nominal)
        // Format EMVCo: ID Tag (54) + 2 Digit Panjang Character + Nominal
        $amountStr = (string) (int) $amount;
        $amountLen = str_pad((string) strlen($amountStr), 2, '0', STR_PAD_LEFT);
        $tag54 = "54{$amountLen}{$amountStr}";

        // 4. Gabungkan Base QR + Tag 54 + Tag 63 (CRC Header) + 04 (Panjang CRC Hex)
        $qrToCalc = $baseQr . $tag54 . "6304";

        // 5. Hitung checksum CRC16 CCITT (False)
        $crcHex = strtoupper(str_pad(dechex($this->crc16ccitt($qrToCalc)), 4, '0', STR_PAD_LEFT));

        // 6. Return string QRIS Dinamis lengkap
        return $qrToCalc . $crcHex;
    }

    /**
     * Algoritma kalkulasi CRC16-CCITT (False) / Polynomial 0x1021
     */
    private function crc16ccitt(string $str): int
    {
        $crc = 0xFFFF;
        $length = strlen($str);

        for ($i = 0; $i < $length; $i++) {
            $crc ^= (ord($str[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if (($crc & 0x8000) !== 0) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return $crc;
    }
}