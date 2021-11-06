<?php

declare(strict_types=1);

namespace Akeneo\Runner\Maintainer;

use PhpSpec\Runner\Maintainer\Maintainer;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Specification;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Exception\Example\SkippingException;

final class SkipExampleMaintainer implements Maintainer
{
    public function supports(ExampleNode $example): bool
    {
        return count($this->getRequirements($this->getSpecDocComment($example))) > 0 || count($this->getRequirements($this->getExampleDocComment($example))) > 0;
    }

    /**
     * @throws SkippingException if a required class or interface are not available
     */
    public function prepare(
        ExampleNode $example,
        Specification $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ): void {
        $this->checkRequirements($this->getRequirements($this->getSpecDocComment($example)));
        $this->checkRequirements($this->getRequirements($this->getExampleDocComment($example)));
    }

    /**
     * @throws SkippingException if a required class or interface are not available
     */
    private function checkRequirements(array $requirements): void
    {
        foreach ($requirements as $requirement) {
            if (!class_exists($requirement) && !interface_exists($requirement)) {
                throw new SkippingException(sprintf('"%s" is not available', $requirement));
            }
        }
    }

    public function teardown(
        ExampleNode $example,
        Specification $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ): void {

    }

    public function getPriority(): int
    {
        return 75;
    }

    /**
     * @return class-string[]
     */
    private function getRequirements(string $docComment): array
    {
        return array_map(
            static function ($tag): ?string {
                preg_match('#@require ([^ ]*)#', $tag, $match);

                return $match[1];
            },
            array_filter(
                array_map('trim', explode("\n", str_replace("\r\n", "\n", $docComment))),
                static fn (string $docline): bool => 0 === strpos($docline, '* @require')
            )
        );
    }

    private function getExampleDocComment(ExampleNode $example): string
    {
        return $example->getFunctionReflection()->getDocComment() ?: '';
    }

    private function getSpecDocComment(ExampleNode $example): string
    {
        return $example->getSpecification()->getClassReflection()->getDocComment() ?: '';
    }
}
