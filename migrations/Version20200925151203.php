<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200925151203 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, tutorial_id INT NOT NULL, user_id INT NOT NULL, content LONGTEXT NOT NULL, picture VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_9474526C89366B7B (tutorial_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cost (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE material (id INT AUTO_INCREMENT NOT NULL, unit_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_7CBE7595F8BD700D (unit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, tutorial_id INT NOT NULL, number SMALLINT DEFAULT NULL, description LONGTEXT DEFAULT NULL, picture_name VARCHAR(255) DEFAULT NULL, video_url VARCHAR(255) DEFAULT NULL, INDEX IDX_43B9FE3C89366B7B (tutorial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tool (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tutorial (id INT AUTO_INCREMENT NOT NULL, level_id INT NOT NULL, cost_id INT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, picture VARCHAR(255) NOT NULL, duration TIME NOT NULL, created_at DATETIME NOT NULL, validation TINYINT(1) NOT NULL, INDEX IDX_C66BFFE95FB14BA7 (level_id), INDEX IDX_C66BFFE91DBF857F (cost_id), INDEX IDX_C66BFFE9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tutorial_tag (tutorial_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_AF0341CF89366B7B (tutorial_id), INDEX IDX_AF0341CFBAD26311 (tag_id), PRIMARY KEY(tutorial_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tutorial_material (tutorial_id INT NOT NULL, material_id INT NOT NULL, INDEX IDX_ACA0E41589366B7B (tutorial_id), INDEX IDX_ACA0E415E308AC6F (material_id), PRIMARY KEY(tutorial_id, material_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tutorial_tool (tutorial_id INT NOT NULL, tool_id INT NOT NULL, INDEX IDX_5F35B99C89366B7B (tutorial_id), INDEX IDX_5F35B99C8F7B22CC (tool_id), PRIMARY KEY(tutorial_id, tool_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, picture_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_tutorial (id INT AUTO_INCREMENT NOT NULL, tutorial_id INT NOT NULL, user_id INT NOT NULL, rate SMALLINT DEFAULT NULL, todo TINYINT(1) DEFAULT NULL, done TINYINT(1) DEFAULT NULL, INDEX IDX_26E61BE989366B7B (tutorial_id), INDEX IDX_26E61BE9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C89366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE material ADD CONSTRAINT FK_7CBE7595F8BD700D FOREIGN KEY (unit_id) REFERENCES unit (id)');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C89366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id)');
        $this->addSql('ALTER TABLE tutorial ADD CONSTRAINT FK_C66BFFE95FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('ALTER TABLE tutorial ADD CONSTRAINT FK_C66BFFE91DBF857F FOREIGN KEY (cost_id) REFERENCES cost (id)');
        $this->addSql('ALTER TABLE tutorial ADD CONSTRAINT FK_C66BFFE9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tutorial_tag ADD CONSTRAINT FK_AF0341CF89366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutorial_tag ADD CONSTRAINT FK_AF0341CFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutorial_material ADD CONSTRAINT FK_ACA0E41589366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutorial_material ADD CONSTRAINT FK_ACA0E415E308AC6F FOREIGN KEY (material_id) REFERENCES material (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutorial_tool ADD CONSTRAINT FK_5F35B99C89366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutorial_tool ADD CONSTRAINT FK_5F35B99C8F7B22CC FOREIGN KEY (tool_id) REFERENCES tool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tutorial ADD CONSTRAINT FK_26E61BE989366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id)');
        $this->addSql('ALTER TABLE user_tutorial ADD CONSTRAINT FK_26E61BE9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tutorial DROP FOREIGN KEY FK_C66BFFE91DBF857F');
        $this->addSql('ALTER TABLE tutorial DROP FOREIGN KEY FK_C66BFFE95FB14BA7');
        $this->addSql('ALTER TABLE tutorial_material DROP FOREIGN KEY FK_ACA0E415E308AC6F');
        $this->addSql('ALTER TABLE tutorial_tag DROP FOREIGN KEY FK_AF0341CFBAD26311');
        $this->addSql('ALTER TABLE tutorial_tool DROP FOREIGN KEY FK_5F35B99C8F7B22CC');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C89366B7B');
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3C89366B7B');
        $this->addSql('ALTER TABLE tutorial_tag DROP FOREIGN KEY FK_AF0341CF89366B7B');
        $this->addSql('ALTER TABLE tutorial_material DROP FOREIGN KEY FK_ACA0E41589366B7B');
        $this->addSql('ALTER TABLE tutorial_tool DROP FOREIGN KEY FK_5F35B99C89366B7B');
        $this->addSql('ALTER TABLE user_tutorial DROP FOREIGN KEY FK_26E61BE989366B7B');
        $this->addSql('ALTER TABLE material DROP FOREIGN KEY FK_7CBE7595F8BD700D');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE tutorial DROP FOREIGN KEY FK_C66BFFE9A76ED395');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A80233D34C1');
        $this->addSql('ALTER TABLE user_tutorial DROP FOREIGN KEY FK_26E61BE9A76ED395');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE cost');
        $this->addSql('DROP TABLE level');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tool');
        $this->addSql('DROP TABLE tutorial');
        $this->addSql('DROP TABLE tutorial_tag');
        $this->addSql('DROP TABLE tutorial_material');
        $this->addSql('DROP TABLE tutorial_tool');
        $this->addSql('DROP TABLE unit');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('DROP TABLE user_tutorial');
    }
}
