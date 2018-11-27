<?php

declare(strict_types=1);

/*
 * This file is part of my Symfony boilerplate,
 * following the Explicit Architecture principles.
 *
 * @link https://herbertograca.com/2017/11/16/explicit-architecture-01-ddd-hexagonal-onion-clean-cqrs-how-i-put-it-all-together
 * @link https://herbertograca.com/2018/07/07/more-than-concentric-layers/
 *
 * (c) Herberto Graça
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\App\Build\Migration;

trait MigrationHelperTrait
{
    private function executeOnlyForDomains(string ...$domainList): void
    {
        $this->skipIf(
            !$this->isCurrentDomainInList(...$domainList),
            'Migration only need to be applied for ' . implode(', ', $domainList) . '.'
        );
    }

    public function executeOnlyForDotCom(): void
    {
        $this->executeOnlyForDomains(ConfigurationAwareMigration::DOMAIN_COM);
    }

    private function isCurrentDomain(string $domain): bool
    {
        return mb_strpos($this->getDomain(), $domain) !== false;
    }

    private function isCurrentDomainInList(string ...$domainList): bool
    {
        foreach ($domainList as $domain) {
            if ($this->isCurrentDomain($domain)) {
                return true;
            }
        }

        return false;
    }

    private function executeOnlyForEnvironments(string ...$environmentList): void
    {
        $this->skipIf(
            !$this->isCurrentEnvironmentInList($environmentList),
            'Migration only need to be applied for ' . implode(', ', $environmentList) . '.'
        );
    }

    /**
     * @param string[] $environmentList
     */
    private function isCurrentEnvironmentInList(array $environmentList): bool
    {
        foreach ($environmentList as $environment) {
            if (mb_strpos($this->getEnvironment(), $environment) !== false) {
                return true;
            }
        }

        return false;
    }

    abstract protected function getDomain(): string;

    abstract protected function getEnvironment(): string;
}
