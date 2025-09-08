<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250905085556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66A76ED395 ON article (user_id)');
        $this->addSql('ALTER TABLE comment ADD user_id INT NOT NULL, ADD article_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('CREATE INDEX IDX_9474526C7294869C ON comment (article_id)');
        $this->addSql('ALTER TABLE favorite ADD user_id INT NOT NULL, ADD soap_id INT NOT NULL');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9EF234A04 FOREIGN KEY (soap_id) REFERENCES soap (id)');
        $this->addSql('CREATE INDEX IDX_68C58ED9A76ED395 ON favorite (user_id)');
        $this->addSql('CREATE INDEX IDX_68C58ED9EF234A04 ON favorite (soap_id)');
        $this->addSql('ALTER TABLE media ADD user_id INT NOT NULL, ADD article_id INT NOT NULL, ADD soap_id INT NOT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CEF234A04 FOREIGN KEY (soap_id) REFERENCES soap (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CA76ED395 ON media (user_id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10C7294869C ON media (article_id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CEF234A04 ON media (soap_id)');
        $this->addSql('ALTER TABLE rating ADD user_id INT NOT NULL, ADD soap_id INT NOT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622EF234A04 FOREIGN KEY (soap_id) REFERENCES soap (id)');
        $this->addSql('CREATE INDEX IDX_D8892622A76ED395 ON rating (user_id)');
        $this->addSql('CREATE INDEX IDX_D8892622EF234A04 ON rating (soap_id)');
        $this->addSql('ALTER TABLE reaction ADD user_id INT NOT NULL, ADD comment_id INT NOT NULL');
        $this->addSql('ALTER TABLE reaction ADD CONSTRAINT FK_A4D707F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reaction ADD CONSTRAINT FK_A4D707F7F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('CREATE INDEX IDX_A4D707F7A76ED395 ON reaction (user_id)');
        $this->addSql('CREATE INDEX IDX_A4D707F7F8697D13 ON reaction (comment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9EF234A04');
        $this->addSql('DROP INDEX IDX_68C58ED9A76ED395 ON favorite');
        $this->addSql('DROP INDEX IDX_68C58ED9EF234A04 ON favorite');
        $this->addSql('ALTER TABLE favorite DROP user_id, DROP soap_id');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('DROP INDEX IDX_23A0E66A76ED395 ON article');
        $this->addSql('ALTER TABLE article DROP user_id');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('DROP INDEX IDX_9474526CA76ED395 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C7294869C ON comment');
        $this->addSql('ALTER TABLE comment DROP user_id, DROP article_id');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CA76ED395');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C7294869C');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CEF234A04');
        $this->addSql('DROP INDEX IDX_6A2CA10CA76ED395 ON media');
        $this->addSql('DROP INDEX IDX_6A2CA10C7294869C ON media');
        $this->addSql('DROP INDEX IDX_6A2CA10CEF234A04 ON media');
        $this->addSql('ALTER TABLE media DROP user_id, DROP article_id, DROP soap_id');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622EF234A04');
        $this->addSql('DROP INDEX IDX_D8892622A76ED395 ON rating');
        $this->addSql('DROP INDEX IDX_D8892622EF234A04 ON rating');
        $this->addSql('ALTER TABLE rating DROP user_id, DROP soap_id');
        $this->addSql('ALTER TABLE reaction DROP FOREIGN KEY FK_A4D707F7A76ED395');
        $this->addSql('ALTER TABLE reaction DROP FOREIGN KEY FK_A4D707F7F8697D13');
        $this->addSql('DROP INDEX IDX_A4D707F7A76ED395 ON reaction');
        $this->addSql('DROP INDEX IDX_A4D707F7F8697D13 ON reaction');
        $this->addSql('ALTER TABLE reaction DROP user_id, DROP comment_id');
    }
}
