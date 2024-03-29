<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240308130122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe CHANGE section section VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE cours CHANGE titre titre VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE objectif objectif VARCHAR(255) DEFAULT NULL, CHANGE duree duree DOUBLE PRECISION DEFAULT NULL, CHANGE ressource ressource VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE creneau CHANGE fin fin DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE formation CHANGE libelle libelle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE image CHANGE content_type content_type VARCHAR(255) DEFAULT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE message message VARCHAR(255) DEFAULT NULL, CHANGE time time DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE question CHANGE reponse_qcm reponse_qcm LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE intitule intitule VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse CHANGE reponse_qcm reponse_qcm LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE reponse_libre reponse_libre VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe CHANGE section section VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE cours CHANGE titre titre VARCHAR(255) DEFAULT \'NULL\', CHANGE description description VARCHAR(255) DEFAULT \'NULL\', CHANGE objectif objectif VARCHAR(255) DEFAULT \'NULL\', CHANGE duree duree DOUBLE PRECISION DEFAULT \'NULL\', CHANGE ressource ressource VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE creneau CHANGE fin fin DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE formation CHANGE libelle libelle VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE image CHANGE content_type content_type VARCHAR(255) DEFAULT \'NULL\', CHANGE name name VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE message CHANGE message message VARCHAR(255) DEFAULT \'NULL\', CHANGE time time DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE question CHANGE intitule intitule VARCHAR(255) DEFAULT \'NULL\', CHANGE reponse_qcm reponse_qcm LONGTEXT DEFAULT \'NULL\' COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE reponse CHANGE reponse_qcm reponse_qcm LONGTEXT DEFAULT \'NULL\' COMMENT \'(DC2Type:array)\', CHANGE reponse_libre reponse_libre VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) DEFAULT \'NULL\', CHANGE nom nom VARCHAR(255) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(255) DEFAULT \'NULL\', CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
