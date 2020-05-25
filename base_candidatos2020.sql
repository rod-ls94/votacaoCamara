create database pesquisa2020;
use pesquisa2020;

CREATE TABLE vereador (id int not null auto_increment primary key,
			nome varchar(50),
                        partido varchar(25));
                        
CREATE TABLE usuario (id int not null auto_increment primary key,
			nome varchar(50),
                        cpf int);
                        
CREATE TABLE votos (id_voto int not null auto_increment primary key,
		    id_vereador int,
                    id_usuario int,
                    foreign key (id_vereador) REFERENCES vereador(id),
                    foreign key (id_usuario) REFERENCES usuario(id));
                    
INSERT INTO vereador (nome, partido)
VALUES  ('Paulinho Lenha', 'PSB'),
	('Eduardo Nascimento', 'PSB'),
        ('Arildo Gomes', 'PDT'),
        ('Fernando Jão', 'PSDB'),
        ('Tim', 'PMDB'),
        ('Pedinha', 'PROS'),
        ('Celso Itiki', 'PSD'),
        ('Marcos Nena', 'PMDB'),
        ('Dr. Castro Andrade', 'PSD'),
        ('Edson Silva', 'PRB'),
        ('Marcinho Prates', 'SD'),
        ('Sandrinho', 'SD'),
        ('Professor Osmar', 'PV');
        
select * from vereador;
