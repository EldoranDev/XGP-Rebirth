<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207205106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `option` (name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `planet` (planet_id INT AUTO_INCREMENT NOT NULL, last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', destroyed TINYINT(1) NOT NULL, fields_current INT NOT NULL, buildings JSON NOT NULL, build_queue JSON NOT NULL, name VARCHAR(255) NOT NULL, user_id INT NOT NULL, image VARCHAR(255) NOT NULL, type INT NOT NULL, resources JSON NOT NULL, resources_per_hour JSON NOT NULL, energy_used INT NOT NULL, energy_max INT NOT NULL, galaxy INT NOT NULL, `system` INT NOT NULL, position INT NOT NULL, diameter INT NOT NULL, fields_max INT NOT NULL, temperature_min INT NOT NULL, temperature_max INT NOT NULL, UNIQUE INDEX UNIQ_PLANET (galaxy, `system`, position), PRIMARY KEY(planet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE premium (user_id INT NOT NULL, dark_matter INT NOT NULL, officier_commander INT NOT NULL, officier_admiral INT NOT NULL, officier_engineer INT NOT NULL, officier_geologist INT NOT NULL, officier_technocrat INT NOT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE research (user_id INT NOT NULL, current VARCHAR(255) DEFAULT NULL, espionage_technology INT NOT NULL, computer_technology INT NOT NULL, weapons_technology INT NOT NULL, shielding_technology INT NOT NULL, armor_technology INT NOT NULL, energy_technology INT NOT NULL, hyperspace_technology INT NOT NULL, combustion_drive INT NOT NULL, impulse_drive INT NOT NULL, hyperspace_drive INT NOT NULL, laser_technology INT NOT NULL, ionic_technology INT NOT NULL, plasma_technology INT NOT NULL, intergalactic_research_network INT NOT NULL, astrophysics INT NOT NULL, graviton_technology INT NOT NULL, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistic (user_id INT NOT NULL, buildings_points DOUBLE PRECISION NOT NULL, buildings_old_rank INT NOT NULL, buildings_rank INT NOT NULL, defense_points DOUBLE PRECISION NOT NULL, defense_old_rank INT NOT NULL, defense_rank INT NOT NULL, ships_points DOUBLE PRECISION NOT NULL, ships_old_rank INT NOT NULL, ships_rank INT NOT NULL, technology_points DOUBLE PRECISION NOT NULL, technology_old_rank INT NOT NULL, technology_rank INT NOT NULL, total_points DOUBLE PRECISION NOT NULL, total_oldrank INT NOT NULL, total_rank INT NOT NULL, update_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (user_id INT AUTO_INCREMENT NOT NULL, home_planet_id INT DEFAULT NULL, current_planet_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(255) NOT NULL, `password` VARCHAR(255) NOT NULL, roles JSON NOT NULL, banned TINYINT(1) NOT NULL, galaxy INT NOT NULL, `system` INT NOT NULL, position INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649DC8AE164 (home_planet_id), UNIQUE INDEX UNIQ_8D93D6497978298C (current_planet_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE premium ADD CONSTRAINT FK_893D1485A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (user_id)');
        $this->addSql('ALTER TABLE research ADD CONSTRAINT FK_57EB50C2A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (user_id)');
        $this->addSql('ALTER TABLE statistic ADD CONSTRAINT FK_649B469CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (user_id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649DC8AE164 FOREIGN KEY (home_planet_id) REFERENCES `planet` (planet_id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6497978298C FOREIGN KEY (current_planet_id) REFERENCES `planet` (planet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE premium DROP FOREIGN KEY FK_893D1485A76ED395');
        $this->addSql('ALTER TABLE research DROP FOREIGN KEY FK_57EB50C2A76ED395');
        $this->addSql('ALTER TABLE statistic DROP FOREIGN KEY FK_649B469CA76ED395');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649DC8AE164');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6497978298C');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE `planet`');
        $this->addSql('DROP TABLE premium');
        $this->addSql('DROP TABLE research');
        $this->addSql('DROP TABLE statistic');
        $this->addSql('DROP TABLE `user`');
    }
}
