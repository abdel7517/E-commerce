<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200830192535 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE img DROP FOREIGN KEY FK_BBC2C8ACD07ECCB6');
        $this->addSql('DROP TABLE adverts');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_BBC2C8ACD07ECCB6 ON img');
        $this->addSql('ALTER TABLE img CHANGE advert_id article_id INT NOT NULL');
        $this->addSql('ALTER TABLE img ADD CONSTRAINT FK_BBC2C8AC7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BBC2C8AC7294869C ON img (article_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adverts (id INT AUTO_INCREMENT NOT NULL, tittle VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, description VARCHAR(1000) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci, roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE img DROP FOREIGN KEY FK_BBC2C8AC7294869C');
        $this->addSql('DROP INDEX UNIQ_BBC2C8AC7294869C ON img');
        $this->addSql('ALTER TABLE img CHANGE article_id advert_id INT NOT NULL');
        $this->addSql('ALTER TABLE img ADD CONSTRAINT FK_BBC2C8ACD07ECCB6 FOREIGN KEY (advert_id) REFERENCES adverts (id)');
        $this->addSql('CREATE INDEX IDX_BBC2C8ACD07ECCB6 ON img (advert_id)');
    }
}
