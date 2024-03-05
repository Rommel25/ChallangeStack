<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305134115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe CHANGE section section VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cours CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE objectif objectif VARCHAR(255) DEFAULT NULL, CHANGE duree duree DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE creneau CHANGE fin fin DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE eleve ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ECA105F7A76ED395 ON eleve (user_id)');
        $this->addSql('ALTER TABLE formateur ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formateur ADD CONSTRAINT FK_ED767E4FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ED767E4FA76ED395 ON formateur (user_id)');
        $this->addSql('ALTER TABLE formation CHANGE libelle libelle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE message message VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE question CHANGE type type VARCHAR(255) DEFAULT NULL, CHANGE reponse_qcm reponse_qcm LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE reponse CHANGE reponse_qcm reponse_qcm LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE reponse_libre reponse_libre VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649155D8F51');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A6CC7B2');
        $this->addSql('DROP INDEX UNIQ_8D93D649A6CC7B2 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649155D8F51 ON user');
        $this->addSql('ALTER TABLE user DROP eleve_id, DROP formateur_id, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe CHANGE section section VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE cours CHANGE titre titre VARCHAR(255) DEFAULT \'NULL\', CHANGE description description VARCHAR(255) DEFAULT \'NULL\', CHANGE objectif objectif VARCHAR(255) DEFAULT \'NULL\', CHANGE duree duree DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE creneau CHANGE fin fin DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F7A76ED395');
        $this->addSql('DROP INDEX UNIQ_ECA105F7A76ED395 ON eleve');
        $this->addSql('ALTER TABLE eleve DROP user_id');
        $this->addSql('ALTER TABLE formateur DROP FOREIGN KEY FK_ED767E4FA76ED395');
        $this->addSql('DROP INDEX UNIQ_ED767E4FA76ED395 ON formateur');
        $this->addSql('ALTER TABLE formateur DROP user_id');
        $this->addSql('ALTER TABLE formation CHANGE libelle libelle VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE message CHANGE message message VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE question CHANGE type type VARCHAR(255) DEFAULT \'NULL\', CHANGE reponse_qcm reponse_qcm LONGTEXT DEFAULT \'NULL\' COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE reponse CHANGE reponse_qcm reponse_qcm LONGTEXT DEFAULT \'NULL\' COMMENT \'(DC2Type:array)\', CHANGE reponse_libre reponse_libre VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user ADD eleve_id INT DEFAULT NULL, ADD formateur_id INT DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT \'NULL\', CHANGE nom nom VARCHAR(255) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(255) DEFAULT \'NULL\', CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A6CC7B2 ON user (eleve_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649155D8F51 ON user (formateur_id)');
    }
}
