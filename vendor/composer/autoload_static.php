<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6b7cccdff2f64702f98678b88aaa5d22
{
    public static $files = array (
        '5255c38a0faeba867671b61dfda6d864' => __DIR__ . '/..' . '/paragonie/random_compat/lib/random.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Mpdf\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Mpdf\\' => 
        array (
            0 => __DIR__ . '/..' . '/mpdf/mpdf/src',
        ),
    );

    public static $classMap = array (
        'FPDF_TPL' => __DIR__ . '/..' . '/setasign/fpdi/fpdf_tpl.php',
        'FPDI' => __DIR__ . '/..' . '/setasign/fpdi/fpdi.php',
        'FilterASCII85' => __DIR__ . '/..' . '/setasign/fpdi/filters/FilterASCII85.php',
        'FilterASCIIHexDecode' => __DIR__ . '/..' . '/setasign/fpdi/filters/FilterASCIIHexDecode.php',
        'FilterLZW' => __DIR__ . '/..' . '/setasign/fpdi/filters/FilterLZW.php',
        'Mpdf\\Barcode' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode.php',
        'Mpdf\\Barcode\\AbstractBarcode' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/AbstractBarcode.php',
        'Mpdf\\Barcode\\BarcodeException' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/BarcodeException.php',
        'Mpdf\\Barcode\\BarcodeInterface' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/BarcodeInterface.php',
        'Mpdf\\Barcode\\Codabar' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/Codabar.php',
        'Mpdf\\Barcode\\Code11' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/Code11.php',
        'Mpdf\\Barcode\\Code128' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/Code128.php',
        'Mpdf\\Barcode\\Code39' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/Code39.php',
        'Mpdf\\Barcode\\Code93' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/Code93.php',
        'Mpdf\\Barcode\\EanExt' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/EanExt.php',
        'Mpdf\\Barcode\\EanUpc' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/EanUpc.php',
        'Mpdf\\Barcode\\I25' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/I25.php',
        'Mpdf\\Barcode\\Imb' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/Imb.php',
        'Mpdf\\Barcode\\Msi' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/Msi.php',
        'Mpdf\\Barcode\\Postnet' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/Postnet.php',
        'Mpdf\\Barcode\\Rm4Scc' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/Rm4Scc.php',
        'Mpdf\\Barcode\\S25' => __DIR__ . '/..' . '/mpdf/mpdf/src/Barcode/S25.php',
        'Mpdf\\Cache' => __DIR__ . '/..' . '/mpdf/mpdf/src/Cache.php',
        'Mpdf\\Color\\ColorConverter' => __DIR__ . '/..' . '/mpdf/mpdf/src/Color/ColorConverter.php',
        'Mpdf\\Color\\ColorModeConverter' => __DIR__ . '/..' . '/mpdf/mpdf/src/Color/ColorModeConverter.php',
        'Mpdf\\Color\\ColorSpaceRestrictor' => __DIR__ . '/..' . '/mpdf/mpdf/src/Color/ColorSpaceRestrictor.php',
        'Mpdf\\Color\\NamedColors' => __DIR__ . '/..' . '/mpdf/mpdf/src/Color/NamedColors.php',
        'Mpdf\\Config\\ConfigVariables' => __DIR__ . '/..' . '/mpdf/mpdf/src/Config/ConfigVariables.php',
        'Mpdf\\Config\\FontVariables' => __DIR__ . '/..' . '/mpdf/mpdf/src/Config/FontVariables.php',
        'Mpdf\\Conversion\\DecToAlpha' => __DIR__ . '/..' . '/mpdf/mpdf/src/Conversion/DecToAlpha.php',
        'Mpdf\\Conversion\\DecToCjk' => __DIR__ . '/..' . '/mpdf/mpdf/src/Conversion/DecToCjk.php',
        'Mpdf\\Conversion\\DecToHebrew' => __DIR__ . '/..' . '/mpdf/mpdf/src/Conversion/DecToHebrew.php',
        'Mpdf\\Conversion\\DecToOther' => __DIR__ . '/..' . '/mpdf/mpdf/src/Conversion/DecToOther.php',
        'Mpdf\\Conversion\\DecToRoman' => __DIR__ . '/..' . '/mpdf/mpdf/src/Conversion/DecToRoman.php',
        'Mpdf\\CssManager' => __DIR__ . '/..' . '/mpdf/mpdf/src/CssManager.php',
        'Mpdf\\Css\\Border' => __DIR__ . '/..' . '/mpdf/mpdf/src/Css/Border.php',
        'Mpdf\\Css\\DefaultCss' => __DIR__ . '/..' . '/mpdf/mpdf/src/Css/DefaultCss.php',
        'Mpdf\\Css\\TextVars' => __DIR__ . '/..' . '/mpdf/mpdf/src/Css/TextVars.php',
        'Mpdf\\DirectWrite' => __DIR__ . '/..' . '/mpdf/mpdf/src/DirectWrite.php',
        'Mpdf\\Exception\\InvalidArgumentException' => __DIR__ . '/..' . '/mpdf/mpdf/src/Exception/InvalidArgumentException.php',
        'Mpdf\\Fonts\\FontCache' => __DIR__ . '/..' . '/mpdf/mpdf/src/Fonts/FontCache.php',
        'Mpdf\\Fonts\\FontFileFinder' => __DIR__ . '/..' . '/mpdf/mpdf/src/Fonts/FontFileFinder.php',
        'Mpdf\\Fonts\\GlyphOperator' => __DIR__ . '/..' . '/mpdf/mpdf/src/Fonts/GlyphOperator.php',
        'Mpdf\\Fonts\\MetricsGenerator' => __DIR__ . '/..' . '/mpdf/mpdf/src/Fonts/MetricsGenerator.php',
        'Mpdf\\Form' => __DIR__ . '/..' . '/mpdf/mpdf/src/Form.php',
        'Mpdf\\Gif\\ColorTable' => __DIR__ . '/..' . '/mpdf/mpdf/src/Gif/ColorTable.php',
        'Mpdf\\Gif\\FileHeader' => __DIR__ . '/..' . '/mpdf/mpdf/src/Gif/FileHeader.php',
        'Mpdf\\Gif\\Gif' => __DIR__ . '/..' . '/mpdf/mpdf/src/Gif/Gif.php',
        'Mpdf\\Gif\\Image' => __DIR__ . '/..' . '/mpdf/mpdf/src/Gif/Image.php',
        'Mpdf\\Gif\\ImageHeader' => __DIR__ . '/..' . '/mpdf/mpdf/src/Gif/ImageHeader.php',
        'Mpdf\\Gif\\Lzw' => __DIR__ . '/..' . '/mpdf/mpdf/src/Gif/Lzw.php',
        'Mpdf\\Gradient' => __DIR__ . '/..' . '/mpdf/mpdf/src/Gradient.php',
        'Mpdf\\Hyphenator' => __DIR__ . '/..' . '/mpdf/mpdf/src/Hyphenator.php',
        'Mpdf\\Image\\Bmp' => __DIR__ . '/..' . '/mpdf/mpdf/src/Image/Bmp.php',
        'Mpdf\\Image\\ImageProcessor' => __DIR__ . '/..' . '/mpdf/mpdf/src/Image/ImageProcessor.php',
        'Mpdf\\Image\\ImageTypeGuesser' => __DIR__ . '/..' . '/mpdf/mpdf/src/Image/ImageTypeGuesser.php',
        'Mpdf\\Image\\Svg' => __DIR__ . '/..' . '/mpdf/mpdf/src/Image/Svg.php',
        'Mpdf\\Image\\Wmf' => __DIR__ . '/..' . '/mpdf/mpdf/src/Image/Wmf.php',
        'Mpdf\\Language\\LanguageToFont' => __DIR__ . '/..' . '/mpdf/mpdf/src/Language/LanguageToFont.php',
        'Mpdf\\Language\\LanguageToFontInterface' => __DIR__ . '/..' . '/mpdf/mpdf/src/Language/LanguageToFontInterface.php',
        'Mpdf\\Language\\ScriptToLanguage' => __DIR__ . '/..' . '/mpdf/mpdf/src/Language/ScriptToLanguage.php',
        'Mpdf\\Language\\ScriptToLanguageInterface' => __DIR__ . '/..' . '/mpdf/mpdf/src/Language/ScriptToLanguageInterface.php',
        'Mpdf\\Log\\Context' => __DIR__ . '/..' . '/mpdf/mpdf/src/Log/Context.php',
        'Mpdf\\Meter' => __DIR__ . '/..' . '/mpdf/mpdf/src/Meter.php',
        'Mpdf\\Mpdf' => __DIR__ . '/..' . '/mpdf/mpdf/src/Mpdf.php',
        'Mpdf\\MpdfException' => __DIR__ . '/..' . '/mpdf/mpdf/src/MpdfException.php',
        'Mpdf\\MpdfImageException' => __DIR__ . '/..' . '/mpdf/mpdf/src/MpdfImageException.php',
        'Mpdf\\Otl' => __DIR__ . '/..' . '/mpdf/mpdf/src/Otl.php',
        'Mpdf\\OtlDump' => __DIR__ . '/..' . '/mpdf/mpdf/src/OtlDump.php',
        'Mpdf\\Output\\Destination' => __DIR__ . '/..' . '/mpdf/mpdf/src/Output/Destination.php',
        'Mpdf\\PageFormat' => __DIR__ . '/..' . '/mpdf/mpdf/src/PageFormat.php',
        'Mpdf\\Pdf\\Protection' => __DIR__ . '/..' . '/mpdf/mpdf/src/Pdf/Protection.php',
        'Mpdf\\Pdf\\Protection\\UniqidGenerator' => __DIR__ . '/..' . '/mpdf/mpdf/src/Pdf/Protection/UniqidGenerator.php',
        'Mpdf\\QrCode\\QrCode' => __DIR__ . '/..' . '/mpdf/mpdf/src/QrCode/QrCode.php',
        'Mpdf\\QrCode\\QrCodeException' => __DIR__ . '/..' . '/mpdf/mpdf/src/QrCode/QrCodeException.php',
        'Mpdf\\Shaper\\Indic' => __DIR__ . '/..' . '/mpdf/mpdf/src/Shaper/Indic.php',
        'Mpdf\\Shaper\\Myanmar' => __DIR__ . '/..' . '/mpdf/mpdf/src/Shaper/Myanmar.php',
        'Mpdf\\Shaper\\Sea' => __DIR__ . '/..' . '/mpdf/mpdf/src/Shaper/Sea.php',
        'Mpdf\\SizeConverter' => __DIR__ . '/..' . '/mpdf/mpdf/src/SizeConverter.php',
        'Mpdf\\TTFontFile' => __DIR__ . '/..' . '/mpdf/mpdf/src/TTFontFile.php',
        'Mpdf\\TTFontFileAnalysis' => __DIR__ . '/..' . '/mpdf/mpdf/src/TTFontFileAnalysis.php',
        'Mpdf\\TableOfContents' => __DIR__ . '/..' . '/mpdf/mpdf/src/TableOfContents.php',
        'Mpdf\\Tag' => __DIR__ . '/..' . '/mpdf/mpdf/src/Tag.php',
        'Mpdf\\Ucdn' => __DIR__ . '/..' . '/mpdf/mpdf/src/Ucdn.php',
        'Mpdf\\Utils\\Arrays' => __DIR__ . '/..' . '/mpdf/mpdf/src/Utils/Arrays.php',
        'Mpdf\\Utils\\NumericString' => __DIR__ . '/..' . '/mpdf/mpdf/src/Utils/NumericString.php',
        'Mpdf\\Utils\\PdfDate' => __DIR__ . '/..' . '/mpdf/mpdf/src/Utils/PdfDate.php',
        'Mpdf\\Utils\\UtfString' => __DIR__ . '/..' . '/mpdf/mpdf/src/Utils/UtfString.php',
        'Psr\\Log\\AbstractLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/AbstractLogger.php',
        'Psr\\Log\\InvalidArgumentException' => __DIR__ . '/..' . '/psr/log/Psr/Log/InvalidArgumentException.php',
        'Psr\\Log\\LogLevel' => __DIR__ . '/..' . '/psr/log/Psr/Log/LogLevel.php',
        'Psr\\Log\\LoggerAwareInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareInterface.php',
        'Psr\\Log\\LoggerAwareTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareTrait.php',
        'Psr\\Log\\LoggerInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerInterface.php',
        'Psr\\Log\\LoggerTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerTrait.php',
        'Psr\\Log\\NullLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/NullLogger.php',
        'Psr\\Log\\Test\\DummyTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
        'Psr\\Log\\Test\\LoggerInterfaceTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
        'fpdi_pdf_parser' => __DIR__ . '/..' . '/setasign/fpdi/fpdi_pdf_parser.php',
        'pdf_context' => __DIR__ . '/..' . '/setasign/fpdi/pdf_context.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6b7cccdff2f64702f98678b88aaa5d22::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6b7cccdff2f64702f98678b88aaa5d22::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6b7cccdff2f64702f98678b88aaa5d22::$classMap;

        }, null, ClassLoader::class);
    }
}
