<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220303122803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, type_id INT NOT NULL, num VARCHAR(255) NOT NULL, iban VARCHAR(255) NOT NULL, balance DOUBLE PRECISION NOT NULL, creation_date DATE NOT NULL, limit_balance INT DEFAULT NULL, overdraft INT DEFAULT NULL, rate INT DEFAULT NULL, INDEX IDX_7D3656A47E3C61F9 (owner_id), INDEX IDX_7D3656A4C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE account_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, limit_balance INT DEFAULT NULL, overdraft INT DEFAULT NULL, rate INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beneficiary (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, account_id INT NOT NULL, name VARCHAR(255) NOT NULL, added_date DATE NOT NULL, INDEX IDX_7ABF446A7E3C61F9 (owner_id), INDEX IDX_7ABF446A9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_request (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, request_date DATE NOT NULL, verified TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, ex VARCHAR(255) NOT NULL, INDEX IDX_D8698A76C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hire (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, type_id INT NOT NULL, token VARCHAR(255) NOT NULL, date DATE NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_B8017EFC6BF700BD (status_id), INDEX IDX_B8017EFCC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hire_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hire_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modify_profil (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, data VARCHAR(255) NOT NULL, INDEX IDX_CF7D3D24C54C8C93 (type_id), INDEX IDX_CF7D3D24A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modify_profil_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transferts (id INT AUTO_INCREMENT NOT NULL, from_account_id INT DEFAULT NULL, destination_account_id INT DEFAULT NULL, type_id INT DEFAULT NULL, date DATE NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_47A3EBA3B0CF99BD (from_account_id), INDEX IDX_47A3EBA3C652C408 (destination_account_id), INDEX IDX_47A3EBA3C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transferts_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, id_card_front_id INT DEFAULT NULL, id_card_back_id INT DEFAULT NULL, proof_of_address_id INT DEFAULT NULL, advisor_id INT DEFAULT NULL, uuid VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, postal_address VARCHAR(255) NOT NULL, register_date DATE NOT NULL, profil_picture VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, first_mdp TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), UNIQUE INDEX UNIQ_8D93D649ADB5F7FB (id_card_front_id), UNIQUE INDEX UNIQ_8D93D6499D83C0F4 (id_card_back_id), UNIQUE INDEX UNIQ_8D93D6496E7ADFEC (proof_of_address_id), INDEX IDX_8D93D64966D3AD77 (advisor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A47E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4C54C8C93 FOREIGN KEY (type_id) REFERENCES account_type (id)');
        $this->addSql('ALTER TABLE beneficiary ADD CONSTRAINT FK_7ABF446A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE beneficiary ADD CONSTRAINT FK_7ABF446A9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76C54C8C93 FOREIGN KEY (type_id) REFERENCES document_type (id)');
        $this->addSql('ALTER TABLE hire ADD CONSTRAINT FK_B8017EFC6BF700BD FOREIGN KEY (status_id) REFERENCES hire_status (id)');
        $this->addSql('ALTER TABLE hire ADD CONSTRAINT FK_B8017EFCC54C8C93 FOREIGN KEY (type_id) REFERENCES hire_type (id)');
        $this->addSql('ALTER TABLE modify_profil ADD CONSTRAINT FK_CF7D3D24C54C8C93 FOREIGN KEY (type_id) REFERENCES modify_profil_type (id)');
        $this->addSql('ALTER TABLE modify_profil ADD CONSTRAINT FK_CF7D3D24A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE transferts ADD CONSTRAINT FK_47A3EBA3B0CF99BD FOREIGN KEY (from_account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE transferts ADD CONSTRAINT FK_47A3EBA3C652C408 FOREIGN KEY (destination_account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE transferts ADD CONSTRAINT FK_47A3EBA3C54C8C93 FOREIGN KEY (type_id) REFERENCES transferts_type (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649ADB5F7FB FOREIGN KEY (id_card_front_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6499D83C0F4 FOREIGN KEY (id_card_back_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6496E7ADFEC FOREIGN KEY (proof_of_address_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64966D3AD77 FOREIGN KEY (advisor_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beneficiary DROP FOREIGN KEY FK_7ABF446A9B6B5FBA');
        $this->addSql('ALTER TABLE transferts DROP FOREIGN KEY FK_47A3EBA3B0CF99BD');
        $this->addSql('ALTER TABLE transferts DROP FOREIGN KEY FK_47A3EBA3C652C408');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A4C54C8C93');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649ADB5F7FB');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6499D83C0F4');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6496E7ADFEC');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76C54C8C93');
        $this->addSql('ALTER TABLE hire DROP FOREIGN KEY FK_B8017EFC6BF700BD');
        $this->addSql('ALTER TABLE hire DROP FOREIGN KEY FK_B8017EFCC54C8C93');
        $this->addSql('ALTER TABLE modify_profil DROP FOREIGN KEY FK_CF7D3D24C54C8C93');
        $this->addSql('ALTER TABLE transferts DROP FOREIGN KEY FK_47A3EBA3C54C8C93');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A47E3C61F9');
        $this->addSql('ALTER TABLE beneficiary DROP FOREIGN KEY FK_7ABF446A7E3C61F9');
        $this->addSql('ALTER TABLE modify_profil DROP FOREIGN KEY FK_CF7D3D24A76ED395');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64966D3AD77');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE account_type');
        $this->addSql('DROP TABLE beneficiary');
        $this->addSql('DROP TABLE customer_request');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_type');
        $this->addSql('DROP TABLE hire');
        $this->addSql('DROP TABLE hire_status');
        $this->addSql('DROP TABLE hire_type');
        $this->addSql('DROP TABLE modify_profil');
        $this->addSql('DROP TABLE modify_profil_type');
        $this->addSql('DROP TABLE transferts');
        $this->addSql('DROP TABLE transferts_type');
        $this->addSql('DROP TABLE `user`');
    }
}
