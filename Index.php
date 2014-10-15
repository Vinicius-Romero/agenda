<html>
	<head>

		<title>Hello World</title>

		<meta charset="UTF-8">

		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/bootstrap-theme.min.css" />

	</head>
	<body class="container-fluid">
		<?php
			ini_set('display_errors', 'On');
			$dbConfig = include 'config/database.php';
			$pdo = new PDO('mysql:host='.$dbConfig['host'].';dbname='.$dbConfig['dbname'].';charset=utf8', $dbConfig['user'], $dbConfig['pass']);

			class Person {
				public $firstname;
				public $lastname;
				public $age;

				public function getFullname(){
					return $this->firstname." ".$this->lastname;
				}
			}

			$persons = $pdo->query('SELECT * FROM person')->fetchAll(PDO::FETCH_CLASS, 'Person');

		?>
		
		<section class="col-md-12">
			<article class="persons col-md-3">
				<ul>
					<?php foreach ($persons as $person): ?>
						<li data-firstname="<?= $person->firstname ?>" data-lastname="<?= $person->lastname ?>" data-age="<?= $person->age ?>">
							Nome: <?= $person->getFullname() ?> - Idade: <?= $person->age ?>
						</li>
					<?php endforeach ?>
				</ul>
				<br />
			</article>

			<div class="clearfix"></div>

			<form method="post" role="form" class="col-md-3">
				<div class="form-group">
					<label for="firstName">Primeiro Nome</label>
					<input type="text" id="firstname" class="pull-right">
				</div>
				<div class="clearfix"></div>
				<div class="form-group">
					<label for="firstName">Último Nome</label>
					<input type="text" id="lastname" class="pull-right">
				</div>
				<div class="clearfix"></div>
				<div class="form-group">
					<label for="age">Idade</label>
					<input type="text" id="age" class="pull-right">
				</div>
				<div class="clearfix"></div>
				<input type="button" id="submit" value="Salvar" name="submit" class="pull-right" />
				<input type="reset" id="clear" value="Limpar" class="pull-left" />
			</form>
		</section>

		<script src="js/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script>
			$('li','.persons').on('click',function(){
				$('#submit').val('Atualizar');
				$('#firstname').val($(this).data('firstname'));
				$('#lastname').val($(this).data('lastname'));
				$('#age').val($(this).data('age'));
			});
			$('#clear').on('click', function(){
				$('#submit').val('Salvar');
			});
		</script>
	</body>
</html>