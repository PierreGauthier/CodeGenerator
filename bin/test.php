<?php

class Templatifier
{
    protected $fileType = [
        'php_class' => [
            'src/Test.php',
        ],
    ];

    public function getBlock() {
        $indent = "(?:[ \t]*)";
        $commentLine = "(?:{$indent}[\/\*].*\n)";

        $use = "(?:use .*;\n)";
        $constDecl = "(?:{$indent}const .*\n)";
        $propertiesDecl = "(?:{$indent}(?:private|protected|public) \$.*\n)";
        $functionDecl = "(?:{$indent}(?:private|protected|public) function.*\n)";

        return [
            'php_class' => [
                'after_uses'        => "($use)(\n*[^$use])",
                'after_consts'      => "($constDecl)((?:\n|$commentLine)*(?!$constDecl))",
                // 'after_properties'  => "((?:$visibility[^\(\n]*;\n)|.*{\n)(\n*(?: *[\/\*].*\n)*$functionDecl)",
            ]
        ];
    }

    public function test() {
        foreach ($this->fileType as $type => $files) {
            foreach ($files as $file) {

                $fileStr = file_get_contents($file);

                foreach ($this->getBlock()[$type] as $name => $pattern) {
                    $fileStr = preg_replace(
                        "/$pattern/",
                        "$1{% block $name %}{% endblock %}\n$2",
                        $fileStr
                    );
                }

                print($fileStr);

            }
        }
    }
}

$blop = new Templatifier();
$blop->test();
