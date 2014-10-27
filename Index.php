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
				public $id;
				public $firstname;
				public $lastname;
				public $age;

				public function getFullname(){
					return $this->firstname." ".$this->lastname;
				}
			}
			
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				if($_POST['queryStatus'] == 'insert'){
					$query = $pdo->prepare('INSERT INTO person (firstname,lastname,age) values (?,?,?)');
					$query->bindValue(1,$_POST['firstname']);
					$query->bindValue(2,$_POST['lastname']);
					$query->bindValue(3,$_POST['age']);
				} elseif($_POST['queryStatus'] == 'update'){
					$query = $pdo->prepare('UPDATE person set firstname = ?, lastname = ?, age = ? where id = ?');
					$query->bindValue(1,$_POST['firstname']);
					$query->bindValue(2,$_POST['lastname']);
					$query->bindValue(3,$_POST['age']);
					$query->bindValue(4,$_POST['itemId']);
				} elseif($_POST['queryStatus'] == 'delete'){
					$query = $pdo->prepare('DELETE FROM person WHERE ID = ?');
					$query->bindValue(1,$_POST['itemId']);
				}
				if(!$query->execute()){
					var_dump($query->errorInfo());
				}
				
			}

			$persons = $pdo->query('SELECT * FROM person')->fetchAll(PDO::FETCH_CLASS, 'Person');
		?>
		
		<section class="row">
			<article class="persons col-md-4">
				<ul>
					<?php foreach ($persons as $person): ?>
						<li data-firstname="<?= $person->firstname ?>" data-lastname="<?= $person->lastname ?>" data-age="<?= $person->age ?>" data-id="<?= $person->id ?>">
							Nome: <?= $person->getFullname() ?> - Idade: <?= $person->age ?>
						</li>
					<?php endforeach ?>
				</ul>
				<br />
			</article>

			<div class="clearfix"></div>

			<form method="post" role="form" class="col-md-4">
				<input type="hidden" name="itemId">
				<div class="form-group">
					<label for="firstName">Primeiro Nome</label>
					<input type="text" id="firstname" name="firstname" class="pull-right">
				</div>
				<div class="form-group">
					<label for="firstName">Último Nome</label>
					<input type="text" id="lastname" name="lastname" class="pull-right">
				</div>
				<div class="form-group">
					<label for="age">Idade</label>
					<input type="text" id="age" name="age" class="pull-right">
				</div>
				<div class="form-group pull-left col-md-4">
					<label class="radio">
						<input type="radio" name="queryStatus" value="delete">
						Deletar
					</label>
				</div>
				<div class="form-group pull-left col-md-4">
					<label class="radio">
						<input type="radio" id="update" name="queryStatus" value="update">
						Atualizar
					</label>
				</div>
				<div class="form-group pull-left col-md-4">
					<label class="radio">
						<input type="radio" id="insert" name="queryStatus" value="insert" checked="checked">
						Novo
					</label>
				</div>
				<div class="clearfix"></div>
				<input type="submit" id="submit" value="Salvar" name="submit" class="pull-right" />
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
				$('input[name=itemId]').val($(this).data('id'));
				$('input#update').prop('checked','checked');
			});
			$('#clear').on('click', function(){
				$('input#insert').prop('checked','checked');
			});
		</script>
	</body>
</html>