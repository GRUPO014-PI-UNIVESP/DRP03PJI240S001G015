<script>
        function openLightBox() {
            var btn = document.querySelector("a.btn");
            btn.click();
         }
         window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            openLightBox();
            e.returnValue = 'Are you sure you want to leave?';
         });
</script>
<td style="width: 15%">
                <a class="btn btn-sm btn-info" href="16VisualizarMsg.php?id=<?php echo $ler ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book-half" viewBox="0 0 16 16">
                    <path d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
                  </svg></a>
                <!-- Botão para confirmar
                  Aciona Modal -->
                  <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                      <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                    </svg></button>
                  <!-- Modal -->
                <form method="POST" action="">
                  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 style="color: aqua" class="modal-title fs-5" id="exampleModalLabel">Exclusão de Registro</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-6">
                              <label for="" class="form-label" style="font-size: 10px; color:aqua">Departamento</label>
                              <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" value="<?php  ?>" readonly>
                            </div>
                            <div class="col-6">
                              <label for="" class="form-label" style="font-size: 10px; color:aqua">Cargo</label>
                              <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" value="<?php  ?>" readonly>
                            </div>
                          </div>
                          <div class="col-12">
                            <label for="" class="form-label" style="font-size: 10px; color:aqua">Nome</label>
                            <input style="font-size: 12px; text-transform:uppercase" type="text" class="form-control" value="<?php  ?>" readonly>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button style="width: 210px" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar SEM Excluir</button>
                          <input style="width: 210px" class="btn btn-primary" type="submit" id="excluir" name="excluir" value="Confirmar">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>            
              </td>