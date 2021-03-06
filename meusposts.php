<?php
    include_once("conexao.php");

    session_start();

    if(isset($_SESSION["usuario"]) && is_array($_SESSION["usuario"])){
      $id_usuario = $_SESSION["usuario"][0];
    }else{
        header('Location: index.php');
    }
?>

<?php
 require_once 'classes/usuarios.php';
 $u = new usuario;
 $u->conectar("login","localhost","root","");

 if(isset($_POST['titulo'])){

   $titulo = addslashes($_POST['titulo']);
   $autor = addslashes($_POST['autor']);
   $categorias = addslashes($_POST['categorias']);
   $artigo = addslashes($_POST['artigo']);
   date_default_timezone_set('America/Sao_Paulo');
   $data = date('d/m/Y \à\s H:i:s');
   $u->conectar("login","localhost","root","");
   $u ->postar($titulo, $autor, $categorias, $artigo, $data);

}

 $result_post = "SELECT * FROM posts WHERE usuario_id = ".$_SESSION["usuario"][0]." ORDER BY id_post DESC";

 $result_post = $pdo->prepare($result_post);
 $result_post->execute();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>Minhas Postagens</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <a href="paginaInicial.php"><img src="img/logo.png" alt="Logo" class="logo"></a> 
        <nav>
          <ul>
              <li><a href="meusposts.php">Minhas Postagens</a></li>
              <li><a href="novopost.php">Novo Post</a></li>
              <li><a href="sair.php" onclick="return confirm('Deseja mesmo sair ?')">Sair</a></li>
          </ul>
        </nav>
    </header>
    <main style="background-image:url(./img/fundo.jpg);">
        <p id="postsRecentes" style="color: #fff;">Meus Posts:</p> 
        <div class="container">
          <div class="row">
          <?php while($row_post = $result_post->fetch(PDO::FETCH_ASSOC)){?>
            <div class="col-md-4">
              <div class="card text-white bg-dark mb-3" style="max-width: 25rem;">
                <div class="card-header" style="text-align: center;font-size: 25px;"><?php echo $row_post['titulo']; ?></div>
                  <div class="card-body">
                    <p class="card-title">Autor: <?php echo $row_post['autor']; ?></p>
                    <p class="card-text">Categoria: <?php echo $row_post['categorias']; ?></p>
                    <p class="card-text initialism">Postado em: <?php echo $row_post['data']; ?></p>
                    <button type="button" class="btn btn-success float-right"><a href="<?php echo "artigo.php?id_post={$row_post['id_post']}"; ?>" style="color: white;text-decoration:none;">Leia Mais</a></button>
                    <a type="button" class="btn btn-danger" onclick="return confirm('Deseja realmente excluir o artigo ?')" href="<?php echo "excluirPost.php?id_post={$row_post['id_post']}"; ?>">Deletar</a>
                    <a type="button" class="btn btn-primary" href="<?php echo "editaPost.php?id_post={$row_post['id_post']}"; ?>">Editar</a>
                  </div>
              </div>
            </div><!-- Fim da div filho -->
          <?php } ?>
          </div>
        </div>
    </main>
    <footer>
        <div class="conteudo-rodape">
            <img src="img/footer.png" alt="footer"> <br>
            <p style="color: #fff;">Todos os direitos reservados</p> 
            <p style="color: #fff;">© Copyright</p>
        </div>
    </footer>
</body>
<script src="javascript/index.js"></script>
</html>