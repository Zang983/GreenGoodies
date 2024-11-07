<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107093054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_has_product DROP FOREIGN KEY FK_AF0913F012854AC3');
        $this->addSql('ALTER TABLE order_has_product ADD CONSTRAINT FK_AF0913F012854AC312854AC3 FOREIGN KEY (order_reference_id) REFERENCES `order` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_has_product DROP FOREIGN KEY FK_AF0913F012854AC312854AC3');
        $this->addSql('ALTER TABLE order_has_product ADD CONSTRAINT FK_AF0913F012854AC3 FOREIGN KEY (order_reference_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
