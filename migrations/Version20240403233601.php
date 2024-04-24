<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403233601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE centros (id_centro INT AUTO_INCREMENT NOT NULL, nombre_centro VARCHAR(100) NOT NULL, email_centro VARCHAR(150) NOT NULL, direccion_centro VARCHAR(200) NOT NULL, telefono_centro VARCHAR(20) NOT NULL, password_centro VARCHAR(100) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_C14802D85BEFD09B (email_centro), PRIMARY KEY(id_centro)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE citas (id_cita INT AUTO_INCREMENT NOT NULL, id_servicio INT NOT NULL, id_cliente INT NOT NULL, id_empleado INT NOT NULL, id_centro INT NOT NULL, fecha_cita DATETIME NOT NULL, INDEX IDX_B88CF8E59B5D1EBF (id_servicio), INDEX IDX_B88CF8E52A813255 (id_cliente), INDEX IDX_B88CF8E5890253C7 (id_empleado), INDEX IDX_B88CF8E542B686 (id_centro), PRIMARY KEY(id_cita)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clientes (id_cliente INT AUTO_INCREMENT NOT NULL, id_centro INT NOT NULL, nombre_cliente VARCHAR(100) NOT NULL, apellidos_cliente VARCHAR(200) NOT NULL, email_cliente VARCHAR(150) NOT NULL, telefono_cliente VARCHAR(20) NOT NULL, password_cliente VARCHAR(100) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_50FE07D749DCF3EA (email_cliente), INDEX IDX_50FE07D742B686 (id_centro), PRIMARY KEY(id_cliente)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE empleados (id_empleado INT AUTO_INCREMENT NOT NULL, id_centro INT NOT NULL, nombre_empleado VARCHAR(100) NOT NULL, apellidos_empleado VARCHAR(200) NOT NULL, rol_empleado VARCHAR(50) NOT NULL, horario_inicio DATETIME NOT NULL, horario_fin DATETIME NOT NULL, INDEX IDX_9EB2266C42B686 (id_centro), PRIMARY KEY(id_empleado)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servicios (id_servicio INT AUTO_INCREMENT NOT NULL, id_centro INT NOT NULL, nombre_servicio VARCHAR(100) NOT NULL, descripcion_servicio VARCHAR(200) DEFAULT NULL, duracion_servicio INT NOT NULL, precio_servicio DOUBLE PRECISION NOT NULL, INDEX IDX_C07E802F42B686 (id_centro), PRIMARY KEY(id_servicio)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE citas ADD CONSTRAINT FK_B88CF8E59B5D1EBF FOREIGN KEY (id_servicio) REFERENCES servicios (id_servicio)');
        $this->addSql('ALTER TABLE citas ADD CONSTRAINT FK_B88CF8E52A813255 FOREIGN KEY (id_cliente) REFERENCES clientes (id_cliente)');
        $this->addSql('ALTER TABLE citas ADD CONSTRAINT FK_B88CF8E5890253C7 FOREIGN KEY (id_empleado) REFERENCES empleados (id_empleado)');
        $this->addSql('ALTER TABLE citas ADD CONSTRAINT FK_B88CF8E542B686 FOREIGN KEY (id_centro) REFERENCES centros (id_centro)');
        $this->addSql('ALTER TABLE clientes ADD CONSTRAINT FK_50FE07D742B686 FOREIGN KEY (id_centro) REFERENCES centros (id_centro)');
        $this->addSql('ALTER TABLE empleados ADD CONSTRAINT FK_9EB2266C42B686 FOREIGN KEY (id_centro) REFERENCES centros (id_centro)');
        $this->addSql('ALTER TABLE servicios ADD CONSTRAINT FK_C07E802F42B686 FOREIGN KEY (id_centro) REFERENCES centros (id_centro)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE citas DROP FOREIGN KEY FK_B88CF8E59B5D1EBF');
        $this->addSql('ALTER TABLE citas DROP FOREIGN KEY FK_B88CF8E52A813255');
        $this->addSql('ALTER TABLE citas DROP FOREIGN KEY FK_B88CF8E5890253C7');
        $this->addSql('ALTER TABLE citas DROP FOREIGN KEY FK_B88CF8E542B686');
        $this->addSql('ALTER TABLE clientes DROP FOREIGN KEY FK_50FE07D742B686');
        $this->addSql('ALTER TABLE empleados DROP FOREIGN KEY FK_9EB2266C42B686');
        $this->addSql('ALTER TABLE servicios DROP FOREIGN KEY FK_C07E802F42B686');
        $this->addSql('DROP TABLE centros');
        $this->addSql('DROP TABLE citas');
        $this->addSql('DROP TABLE clientes');
        $this->addSql('DROP TABLE empleados');
        $this->addSql('DROP TABLE servicios');
    }
}
