<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Galleries extends CI_Controller{

  /**
   * function  thumbs()
   * Cria os thumbnails da aplicacao
   * @param type $imagem
   * @param type $largura
   * @param type $altura
   * @return type
   */
  public function thumbs($imagem, $largura = NULL, $altura = NULL){
    $config['image_library'] = 'gd2';
    $config['source_image'] = str_replace("-", "/", $imagem);
    $config['maintain_ratio'] = true;
    $config['dynamic_output'] = true;
    $config['quality'] = "72%";
    if( (!is_null($largura)) and (is_numeric($largura)) ){
      $config['width'] = $largura;
    }
    if( (!is_null($altura)) and (is_numeric($altura)) ){
      $config['height'] = $altura;
    }
    $this->load->library('image_lib', $config);
    $this->image_lib->resize()->clear();
  }

  // Número de contatos por página
  private $galeriasPagina = 5;
  // Diretório da pasta onde as imagens estão localizadas
  private $img_dir = "";
  private $galleryPath;
  private $galleryPathURL;
  private $itensMenu;
  // Método construtor
  public function __construct()
  {
    parent::__construct();
    $this->galleryPath = realpath( APPPATH."../uploads/image/" );
    $this->galleryPathURL = base_url()."uploads/image/";
    # Carrega na propriedade a configuração 'pasta_img' config/config.php
    $this->img_dir = base_url() . $this->config->item('pasta_img');
    # Carrega o Model da aplicação
    $this->load->model( ADMINISTRATOR_PATH.'GaleriasModel');
    # Carrega a biblioteca utilizada na aplicação
    $this->load->library('form_validation');
    $this->itensMenu = $this->GaleriasModel->getMenu();
  }

  // Index. Redireciona para 'contatos/all'
  public function index()
  {
    // 'Delega' para o método all() logo abaixo
    $this->all();
  }

  // Exibe todos os contatos
  public function all($de_paginacao=0)
  {
    # Preparando o limite da paginação
    $de_paginacao = ( $de_paginacao < 0 || $de_paginacao == 1 ) ? 0 : (int) $de_paginacao;
    # Carrega as bibliotecas
    $this->load->library(array('pagination', 'table'));
    # Acessa o Model, executa a função get_all() e recebe os contatos
    $contatos = $this->GaleriasModel->get_all($this->galeriasPagina, $de_paginacao);
    # Paginação
    $config_paginacao['base_url']   = site_url(ADMINISTRATOR_PATH.'galerias/all/');
    $config_paginacao['total_rows'] = $this->GaleriasModel->count_rows();
    $config_paginacao['uri_segment'] = 4;
    $config_paginacao['per_page'] = $this->galeriasPagina;
    $this->pagination->initialize($config_paginacao);

    $dados['html_paginacao'] = $this->pagination->create_links();
    # img_dir
    $dados['img_dir'] = $this->img_dir;
    $tmpl = array ( 'table_open'  => '<table cellspacing="0" cellpadding="7" border="0" class="mytable">' );
    $this->table->set_template($tmpl);
    # Itera sobre os contatos retornados e gera uma tabela HTML
    $this->table->set_heading('ID', 'Galeria', 'Chamada', 'Operações');
    foreach ($contatos->result() as $contato)
    {
      $optionsTable = array(
        'class' => 'optionsTable'
        , 'colspan' => 3
        , 'data' => '<a class="c-view left" href="'.site_url( ADMINISTRATOR_PATH.'galerias/visualizar') . '/' . $contato->idGaleria .
        '" title="Visualizar"><img src="'.$dados['img_dir'].'/icones/icon-eye.png" /> </a>' .

        '<a class="c-view left" href="'.site_url( ADMINISTRATOR_PATH.'galerias/fotos') . '/' . $contato->idGaleria .
        '" title="Visualizar"><img src="'.$dados['img_dir'].'/icones/icon-write.png" /> </a>' .

        '<a class="c-del left noMarginRight" href="'.site_url( ADMINISTRATOR_PATH.'galerias/remover') . '/' . $contato->idGaleria .
        '" title="Deletar"><img src="'.$dados['img_dir'].'/icones/icon-remove.png" /> </a>'
        );
      $this->table->add_row
      (
        $contato->idGaleria,
        $contato->titulo,
        $contato->chamada,
        $optionsTable
        );
    }
    $dados['html_contatos'] = $this->table->generate();
    // pegando os dados do menu
    $dados['itensMenu'] = $this->itensMenu;
    # Carrega a view 'agenda'
    $this->load->view(ADMINISTRATOR_PATH.'galerias/index', $dados);
  }

  // Abre a View de visualizar / editar
  public function visualizar($id)
  {
    # Acessa o Model
    $contatos = $this->GaleriasModel->get_by_id($id);
    # Se não encontrou esse contato, o Model retorna FALSE
    if( $contatos==FALSE )
    {
      redirect(ADMINISTRATOR_PATH.'galerias','refresh');
    }
    # Coloca os dados no Array que será enviado para a View
    $dados['idGaleria'] = $contatos->row(0)->idGaleria;
    $dados['titulo'] = $contatos->row(0)->titulo;
    $dados['chamada'] = $contatos->row(0)->chamada;
    # img_dir
    $dados['img_dir'] = $this->img_dir;

    // pegando os dados do menu
    $dados['itensMenu'] = $this->itensMenu;
    # Abre a View
    $this->load->view(ADMINISTRATOR_PATH.'galerias/editar',$dados);
  }

  // Processa uma atualização
  public function atualizar()
  {
        // Inicializando o delimitador dos erros
    $this->form_validation->set_error_delimiters('<div class="erro">','</div>');
    // Inicia com o valor da variável do id
    $idGaleria = (int) $this->input->post('idGaleria', TRUE);
    // Regras de validação
    $this->form_validation->set_rules('titulo', 'titulo', 'required|trim|xss_clean');
    $this->form_validation->set_rules('chamada', 'chamada', 'trim|xss_clean');
    if( $this->form_validation->run()==TRUE ){

      $galleryData = new stdClass();
      $galleryData->titulo = $this->input->post('titulo', TRUE);
      $galleryData->chamada = $this->input->post('chamada', TRUE);
       // $galleryData->idGaleria = $this->input->post('idGaleria', TRUE);
      $galleryData->dataCriacao = date("Y-m-d h:i:s", time());

      # Chama o Model e pede para atualizar o contato
      $resultado = $this->GaleriasModel->update($idGaleria, $galleryData);

      # Seta uma sessão com o resultado do Update ( True ou False )
      if( $resultado === TRUE ){
        $this->session->set_flashdata('mensagem', 'Dados atualizados com sucesso.');
      }else{
        # Não passou nas validações. Abre o formulário para adicionar a agenda
        $this->session->set_flashdata('mensagem', 'Ocorreu um erro na atualização da Galeria.');
      }
    }
    else
    {
      # Não passou nas validações. Abre o formulário para adicionar a agenda
      $this->session->set_flashdata('mensagem', 'Ocorreu um erro na atualização da Galeria.');
    }
    // pegando os dados do menu
    $dados['itensMenu'] = $this->itensMenu;

    # Redireciona
    redirect( ADMINISTRATOR_PATH.'galerias/visualizar/' . $idGaleria, 'refresh');
  }



  // Abre a View de visualizar / editar
  public function fotos($id)
  {
    # Acessa o Model
    $contatos = $this->GaleriasModel->get_by_id($id);
    // Inicializando o delimitador dos erros
    $this->form_validation->set_error_delimiters('<div class="erro">','</div>');
    // Inicia com o valor da variável do id
    //$idGaleria = (int) $this->input->post('idGaleria', TRUE);
    $idGaleria = $contatos->row(0)->idGaleria;
    if($this->input->post('acao') == 'uploadFile'){
      $images = $this->addPhoto($idGaleria, 'userfile');
    }

    if($this->input->post('acao') == 'ordenation'){
      $ordem = $this->input->post('ordem');
      $ordem = str_replace('i_','', $ordem);
      $ordenation = explode(',', $ordem);
      $test = $this->ordenation($idGaleria, $ordenation);

    }
    if($this->input->post('acao') == 'deletePhoto'){

      $idFoto = $this->input->post('idFoto');
      $result = $this->deletePhoto($idFoto);

      if( $result == TRUE )
      {
        $this->session->set_flashdata('mensagem', 'Foto removida com sucesso.');
      }
      else {
        $this->session->set_flashdata('mensagem', 'Houve um erro na exclusao da foto.');
      }
    }
    // pegando os dados das fotos
    $images = $this->getPhotos($idGaleria);
    $dados['images'] = $images;
    # Coloca os dados no Array que será enviado para a View
    $dados['idGaleria'] = $idGaleria;
    $dados['titulo'] = $contatos->row(0)->titulo;
    $dados['chamada'] = $contatos->row(0)->chamada;
    # img_dir
    $dados['img_dir'] = $this->img_dir;
    // pegando os dados do menu
    $dados['itensMenu'] = $this->itensMenu;
    # Abre a View
    $this->load->view(ADMINISTRATOR_PATH.'galerias/fotos', $dados);
  }

  // Abre a View de visualizar / editar
  public function ordenation($id, $ordem)
  {
    # Acessa o Model
    $fotos = $this->GaleriasModel->get_by_id($id);
    $return = FALSE;
    if($fotos->num_rows > 0){
      $count = count($ordem);
      --$count;
      for($i = 1; $count > $i; $i++){
        $ordenar['ordenacao'] = $i;
        $return = $this->GaleriasModel->updatePhoto($ordem[$i], $ordenar);
      }
    }

    return $return;
  }

  // Processa uma atualização
  public function addPhoto($id, $formUpload)
  {

    $configUpload = array(
      'allowed_types' => 'jpg|jpeg|png|gif'
      , 'upload_path' => $this->galleryPath
      , 'encrypt_name' => TRUE
      , 'max_size'  => 20000
      );
    $this->load->library('upload', $configUpload);
    $this->upload->do_upload($formUpload);
    if(!$this->upload->do_upload()){
      $this->session->flashdata('mensagem', $this->upload->display_errors());
    }

    $imageUpload = $this->upload->data();

    $photosData = new stdClass();
    $photosData->foto = $imageUpload['file_name'];
    $photosData->idGaleria = $id;
    $photosData->dataCadastro = date("Y-m-d");
    // inserindo os dados da imagem no BD
    $photos = $this->GaleriasModel->Insert('tbFotos', $photosData);
    $returnImages = $this->getPhotos($id);

    return $returnImages;

     // $this->load->view(ADMINISTRATOR_PATH.'galerias/fotos', $dados);
  }

  public function getPhotos($idGaleria){

    $images = array();
    $imagesGallery = $this->GaleriasModel->getPhotos($idGaleria);

    if(is_array($imagesGallery)){
      foreach($imagesGallery as $file){
        $images[] = array(
          'url'         => $this->galleryPathURL.$file->foto
          , 'thumb_url'     => $this->galleryPathURL.$file->foto
          , 'legenda'     => $file->legenda
          , 'idFoto'      => $file->idFoto
          , 'dataCadastro'  => $file->dataCadastro
          );
      }
    }

    return $images;
  }

  // Remove um contato
  public function remover($id)
  {
    $resultado = $this->GaleriasModel->delete($id);

    if( $resultado==TRUE )
    {
      $this->session->set_flashdata('mensagem', 'Galeria removida com sucesso.');
    }
    // pegando os dados do menu
    $dados['itensMenu'] = $this->itensMenu;
    # Redireciona
    redirect( ADMINISTRATOR_PATH.'galerias', 'refresh');
  }


  // Remove uma foto
  public function deletePhoto($id)
  {
    $resultado = $this->GaleriasModel->deletePhoto($id);
    return  $resultado;
  }
    // Insere um novo contato
  public function adicionar()
  {
    # img_dir
    $dados['img_dir'] = $this->img_dir;
    // pegando os dados do menu
    $dados['itensMenu'] = $this->itensMenu;
        // carregando a view com o form para adicionar a agenda
    $this->load->view( ADMINISTRATOR_PATH.'galerias/adicionar', $dados);
  }

  // Insere um novo contato
  public function cadastrar()
  {
    # img_dir
    $dados['img_dir'] = $this->img_dir;
    # -- Exercício: Crie o processo de cadastro, bem como a View.
        // Inicializando o delimitador dos erros
    $this->form_validation->set_error_delimiters('<div class="erro">','</div>');
    // Regras de validação
    # xss_clean -> Filtra e limpa o dado recebido.
    $this->form_validation->set_rules('titulo', 'titulo', 'required|trim|xss_clean');
    $this->form_validation->set_rules('chamada', 'chamada', 'trim|xss_clean');
    if( $this->form_validation->run() )
    {
          # Chama o Model e pede para atualizar o contato
      $galleryData = new stdClass();
      $galleryData->titulo = $this->input->post('titulo', TRUE);
      $galleryData->chamada = $this->input->post('chamada', TRUE);
      $galleryData->dataCriacao = date("Y-m-d h:i:s", time());

      $resultado = $this->GaleriasModel->insert('tbGalerias', $galleryData);
      if($resultado == FALSE){
        $this->session->set_flashdata('mensagem', 'Ocorreu um erro no cadastro da nova Galeria.');
      }else{
        $this->session->set_flashdata('mensagem', 'Galeria adicionada com sucesso.');
      }
    }
    else
    {
      # Não passou nas validações. Abre o formulário para adicionar a agenda
      $this->session->set_flashdata('mensagem', 'Ocorreu um erro no cadastro da nova Galeria.');
    }
    // pegando os dados do menu
    $dados['itensMenu'] = $this->itensMenu;
    # Passou nas validações e o usuário foi adicionado com sucesso.
    redirect( ADMINISTRATOR_PATH.'galerias/adicionar', $galleryData);
  }

}