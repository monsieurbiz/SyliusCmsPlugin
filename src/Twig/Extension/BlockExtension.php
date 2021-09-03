<?php

/*
 * This file is part of Monsieur Biz' Settings plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusCmsPagePlugin\Twig\Extension;

use Doctrine\ORM\EntityManagerInterface;
use MonsieurBiz\SyliusCmsPagePlugin\Entity\Block;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class BlockExtension extends AbstractExtension
{
    public function __construct(
        public EntityManagerInterface $entityManager,
        public LocaleContextInterface $localeContext,
        public ChannelContextInterface $channelContext
    )
    {
    }

    public function getCmsBlockContent(string $blockCode) {
        $localeCode = $this->localeContext->getLocaleCode();
        $channelCode = $this->channelContext->getChannel()->getCode();

        $block = $this->entityManager->getRepository(Block::class)
            ->findOneEnabledByBlockCodeAndChannelCode($blockCode, $localeCode, $channelCode);

        if ($block !== null) {
            return $block->getContent();
        }

        return null;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getCmsBlockContent', [$this, 'getCmsBlockContent'])
        ];
    }
}