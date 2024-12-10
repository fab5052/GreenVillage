<?php

namespace App\Enum;

use App\Entity\User;
use League\Uri\Contracts\UriAccess;
use PhpParser\Builder\EnumCase;
use PHPUnit\TextUI\CliArguments\Mapper;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcher;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcherInterface;

enum UserRole: string
{
    case ADMIN = 'admin';
    case COMMERCIAL = 'commercial';
    case CLIENT = 'client';
}

   /**
     * Méthode pour obtenir une description de l'utilisateur
     */
    // public function getDescription(): EnumCase
    // {
    //     return match ($this) {
    //         self::ADMIN => 1,
    //         self::COMMERCIAL => 2,
    //         self::CLIENT => 3,
    //     };
    // }

    /**
     * Méthode pour rediriger les utilisateurs dans leur dashboard respectif
     */
    // public function isFinal(): UriAccess
    // {
    //     return match ($this) {
    //         self::ADMIN => RedirectableUrlMatcherInterface($parentPath), 
    //         self::COMMERCIAL => RedirectableUrlMatcherInterface($parentPath),
    //         self::CLIENT => RedirectableUrlMatcherInterface($parentPath),
    //     };
    
    // } 