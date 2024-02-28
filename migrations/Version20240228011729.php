<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228011729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE collaborateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(60) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_770CBCD3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(60) NOT NULL, email VARCHAR(60) NOT NULL, telephone VARCHAR(50) NOT NULL, est_supprime TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, contact_id INT NOT NULL, date DATETIME NOT NULL, action VARCHAR(30) NOT NULL, ancienne_valeur VARCHAR(255) DEFAULT NULL, nouvelle_valeur VARCHAR(255) DEFAULT NULL, INDEX IDX_EDBFD5ECE7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, collaborateur_id INT NOT NULL, contact_id INT NOT NULL, contenu VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_B6BD307FA848E3B1 (collaborateur_id), INDEX IDX_B6BD307FE7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES collaborateur (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5ECE7A1254A');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA848E3B1');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE7A1254A');
        $this->addSql('DROP TABLE collaborateur');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
