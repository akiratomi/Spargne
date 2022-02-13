<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220213205922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D8698A76C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76C54C8C93 FOREIGN KEY (type_id) REFERENCES document_type (id)');
        $this->addSql('ALTER TABLE user ADD id_card_front_id INT DEFAULT NULL, ADD id_card_back_id INT DEFAULT NULL, ADD proof_of_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ADB5F7FB FOREIGN KEY (id_card_front_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499D83C0F4 FOREIGN KEY (id_card_back_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496E7ADFEC FOREIGN KEY (proof_of_address_id) REFERENCES document (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649ADB5F7FB ON user (id_card_front_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6499D83C0F4 ON user (id_card_back_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496E7ADFEC ON user (proof_of_address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649ADB5F7FB');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6499D83C0F4');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6496E7ADFEC');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76C54C8C93');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_type');
        $this->addSql('ALTER TABLE customer_request CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE token token VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE hire CHANGE token token VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE hire_status CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE hire_type CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX UNIQ_8D93D649ADB5F7FB ON `user`');
        $this->addSql('DROP INDEX UNIQ_8D93D6499D83C0F4 ON `user`');
        $this->addSql('DROP INDEX UNIQ_8D93D6496E7ADFEC ON `user`');
        $this->addSql('ALTER TABLE `user` DROP id_card_front_id, DROP id_card_back_id, DROP proof_of_address_id, CHANGE uuid uuid VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE postal_address postal_address VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE profil_picture profil_picture VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE postal_code postal_code VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone_number phone_number VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
