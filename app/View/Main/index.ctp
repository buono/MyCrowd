<!DOCTYPE html>
<html lang="ja">
<head>
    <title>Main page</title>
</head>
<body>
<h1>仕事検索</h1>
<form class="form-horizontal" method="get" action="./fetchRecord">
  <div class="control-group">
    <label class="control-label">方式</label>
    <div class="controls">
      <select name="type">
        <option value="project">プロジェクト</option>
        <option value="compe">コンペ</option>
        <option value="task">タスク</option>
      </select>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">カテゴリ</label>
        <div class="controls">
        <label class="checkbox inline">
          <input type="checkbox" name="category" value="writing">ライティング
        </label>
        <label class="checkbox inline">
          <input type="checkbox" name="category" value="naming">ネーミング・コピー
        </label>
        <label class="checkbox inline">
          <input type="checkbox" name="category" value="edit">編集・校正
        </label>
        <label class="checkbox inline">
          <input type="checkbox" name="category" value="other">その他全て
        </label>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">キーワード</label>
    <div class="controls">
      <input type="text" name="keyword" />
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn">検索</button>
    </div>
  </div>
</form>
</body>
</html>