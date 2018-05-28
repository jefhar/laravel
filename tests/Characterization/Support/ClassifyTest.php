<?php
/**
 * Characterization test for Reliese\Support\Classify.
 *
 * Characterization tests document the current behavior. They prevent further
 * code additions and refactoring from breaking existing behavior.
 */

namespace Test\Reliese\Characterization\Support;

use Reliese\Support\Classify;

class ClassifyTest extends \PHPUnit_Framework_TestCase
{

    public function test_classify_can_be_instantiated()
    {
        $classify = new Classify();
        $this->assertInstanceOf(Classify::class, $classify);
    }

    public function testField()
    {
        $classify = new Classify();
        $field = $classify->field(
            'name',
            'value',
            [
                'before' => 'beforeOption',
                'visibility' => 'visibilityOption',
                'after' => 'afterOption',
            ]);
        $this->assertContains('beforeOption', $field, 'Field is missing `before` option.');
        $this->assertContains("\t", $field, "Field is missing `\t` character.");
        $this->assertContains('visibilityOption', $field, 'Filed is missing `visibility` option.');
        $this->assertContains('afterOption', $field, 'Field is missing `after` option.');
        $this->assertNotContains('"1"', $classify->field('one', [1]), 'Field converts integer array value to string.');
        $this->assertNotContains("'1'", $classify->field('one', [1]), 'Field converts integer array value to string.');
        $this->assertContains(
            "'Reliese\\\\Support\\\\Classify'",
            $classify->field(
                'one',
                [Classify::class]));
    }

    public function testAnnotation()
    {
        $classify = new Classify();
        $annotation = $classify->annotation('name', 'value');
        $this->assertContains('name', $annotation, 'Name value missing from annotation.');
        $this->assertContains('value', $annotation, 'Value value missing from annotation.');
        $this->assertStringStartsWith("\n", $annotation, 'Message failed to begin with `\n`.');
    }

    public function testConstant()
    {
        $classify = new Classify();
        $this->assertContains("const name = 'value'", $classify->constant('name', 'value'),
            'Constant does not make `name` == `value`.');
        $this->assertContains('name = [', $classify->constant('name', []), 'Constant does not correctly parse array.');

    }

    public function testMethod()
    {
        $classify = new Classify();
        $method = $classify->method('name', 'body', ['visibility' => 'public']);
        $this->assertStringStartsWith("\n", $method, 'Method failed to start with `\n`.');
        $this->assertContains('public function name()', $method, 'Default visibility `public` missing.');
        $this->assertContains('private function name()',
            $classify->method('name', 'body', ['visibility' => 'private']),
            'Private visibility failed to override default.');
    }

    public function testMixin()
    {
        $classify = new Classify();
        $this->assertStringStartsWith("\t",
            $classify->mixin('flarp'),
            'Mixin failed to begin with `\t`.');
        $this->assertStringEndsWith("\n",
            $classify->mixin('flarp'),
            'Mixin failed to end with `\n`.');
        $this->assertContains('flarp',
            $classify->mixin('flarp'),
            'Mixin failed to include name of mixin.');
        $this->assertContains("use \\flarp",
            $classify->mixin('\flarp'),
            "Mixin does not add `\\` to mixin beginning with `\\`.");
        $this->assertContains("use \\flarp",
            $classify->mixin("flarp"),
            "Mixin does not add exactly one `\\` before mixin not beginning with `\\`.");
$this->assertContains("use \\Flarp;", $classify->mixin('\\' . 'Flarp'), 'Mixin does not remove leading `\'\\\\\'` 
        from class name.');

    }
}
