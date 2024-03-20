<?php
require __DIR__ . '/parts/admin-required.php';
require __DIR__ . '/parts/pdo-connect.php';
$title = '新增會員列表';
$pageName = 'user list add';


?>
<?php include __DIR__ . '/parts/1_head.php' ?>
<?php include __DIR__ . '/parts/2_nav.php' ?>
<?php include __DIR__ . '/parts/3_side_nav.php' ?>
<div class="container">
  <div class="row mt-3">
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">新增會員列表</h5>
          <form name="list_add" onsubmit="sendData(event)">
            <div class="mb-3">
              <label for="name" class="form-label">姓名</label>
              <input type="text" class="form-control" id="name" name="name">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="account" class="form-label">手機</label>
              <input type="text" class="form-control" id="account" name="account">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">電子郵件</label>
              <input type="text" class="form-control" id="email" name="email">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="pic" class="form-label">照片</label>
              <input type="image" class="form-control" id="pic" name="pic">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="address_detail" class="form-label">地址</label>
              <textarea class="form-control" name="address_detail" id="address_detail" cols="30" rows="3"></textarea>
              <div class="form-text"></div>
            </div>
            <button type="submit" class="btn btn-primary">確認新增</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">資料新增結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          資料新增成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
        <a href="user_list.php" class="btn btn-primary">跳到列表頁</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="failureModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">資料沒有新增</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          資料新增沒有成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
        <a href="user_list.php" class="btn btn-primary">跳到列表頁</a>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/parts/5_script.php' ?>
<script>
  const {
    name: nameField,
    email: emailField,
    account: accountField
  } = document.list_add;

  function validateEmail(email) {
    const re =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

  function validateAccount(account) {
    const pattern = /^09\d{2}-?\d{3}-?\d{3}$/;
    return pattern.test(account);
  }


  function sendData(e) {
    // 欄位的外觀要回復原來的狀態
    nameField.style.border = "1px solid #CCC";
    nameField.nextElementSibling.innerHTML = '';
    emailField.style.border = "1px solid #CCC";
    emailField.nextElementSibling.innerHTML = '';
    accountField.style.border = "1px solid #CCC";
    accountField.nextElementSibling.innerHTML = '';


    e.preventDefault(); // 不要讓有外觀的表單以傳統的方式送出

    let isPass = true; // 有沒有通過檢查, 預設值為 true

    // TODO: 檢查資料的格式

    // 姓名是必填, 長度要 2 以上
    if (nameField.value.length < 2) {
      isPass = false;
      nameField.style.border = "2px solid red";
      nameField.nextElementSibling.innerHTML = '請輸入正確的名字';
    }

    // email 若有填才檢查格式, 沒填不檢查格式
    if (emailField.value && !validateEmail(emailField.value)) {
      isPass = false;
      emailField.style.border = "2px solid red";
      emailField.nextElementSibling.innerHTML = '請輸入正確的 Email';
    }

    // account 若有填才檢查格式, 沒填不檢查格式
    if (accountField.value && !validateAccount(accountField.value)) {
      isPass = false;
      accountField.style.border = "2px solid red";
      accountField.nextElementSibling.innerHTML = '請輸入正確的手機號碼';
    }



    // 如果欄位都有通過檢查, 才要發 AJAX
    if (isPass) {
      const fd = new FormData(document.list_add); // 看成沒有外觀的表單

      fetch('user_list_add-api.php', {
          method: 'POST',
          body: fd
        })
        .then(r => r.json())
        .then(result => {
          console.log(result);
          if (result.success) {
            alert('資料新增成功')
            successModal.show();
          } else {
            alert('資料新增失敗')
            if (result.error) {
              failureInfo.innerHTML = result.error;
            } else {
              failureInfo.innerHTML = '資料新增沒有成功';
            }
            failureModal.show();
          }
        })
        .catch(ex => {
          console.log(ex);
          // alert('資料新增發生錯誤' + ex)
          failureInfo.innerHTML = '資料新增發生錯誤' + ex;
          failureModal.show();
        })
    }
  }

  const successModal = new bootstrap.Modal('#successModal');
  const failureModal = new bootstrap.Modal('#failureModal');
  const failureInfo = document.querySelector('#failureModal .alert-danger');
</script>
<?php include __DIR__ . '/parts/6_foot.php' ?>