<?php
/*
 * Copyright (c) 2025 SeeGap Ltd. (https://seegap.com/)
 *
 * This software is licensed to SeeGap Ltd..
 * Licensed software, not for unauthorized distribution or use.
 *
 */

namespace SeeGap\QrCodes;

use BaconQrCode\Renderer\Eye\EyeInterface;
use BaconQrCode\Renderer\Path\Path;
use SimpleSoftwareIO\QrCode\Singleton;

final class OctagonEye implements EyeInterface, Singleton
{
    private static $instance;

    private function __construct()
    {
    }

    public static function instance() : self
    {
        return self::$instance ?: self::$instance = new self();
    }

    public function getExternalPath(): Path
    {
        $path = new Path();

        $outer_radius = 3.5;
        $inner_radius = 2.5;
        $sides = 8;

        /* outer octagon */
        for ($i = 0; $i < $sides; $i++) {
            $angle = deg2rad(45 * $i - 22.5); /* start at -22.5° for flat top */
            $x = cos($angle) * $outer_radius;
            $y = sin($angle) * $outer_radius;

            if($i === 0) {
                $path = $path->move($x, $y);
            } else {
                $path = $path->line($x, $y);
            }
        }
        $path = $path->close();

        /* inner octagon (cutout) */
        for ($i = 0; $i < $sides; $i++) {
            $angle = deg2rad(45 * $i - 22.5);
            $x = cos($angle) * $inner_radius;
            $y = sin($angle) * $inner_radius;

            if($i === 0) {
                $path = $path->move($x, $y);
            } else {
                $path = $path->line($x, $y);
            }
        }

        return $path->close();
    }

    public function getInternalPath(): Path
    {
        $path = new Path();

        $radius = 1.5;
        $sides = 8;

        for ($i = 0; $i < $sides; $i++) {
            $angle = deg2rad(45 * $i - 22.5);
            $x = cos($angle) * $radius;
            $y = sin($angle) * $radius;

            if($i === 0) {
                $path = $path->move($x, $y);
            } else {
                $path = $path->line($x, $y);
            }
        }

        return $path->close();
    }
}
