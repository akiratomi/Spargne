<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217232659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modify_profil (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) NOT NULL, data VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modify_profil_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE modify_profil');
        $this->addSql('DROP TABLE modify_profil_type');
        $this->addSql('ALTER TABLE customer_request CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE token token VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE document CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE ex ex VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE document_type CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE hire CHANGE token token VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE hire_status CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE hire_type CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `user` CHANGE uuid uuid VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE postal_address postal_address VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE profil_picture profil_picture VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE postal_code postal_code VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone_number phone_number VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}