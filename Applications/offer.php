<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<form>
  <div class="form-group row">
    <label for="ordrenr" class="col-4 col-form-label">Ordrenr</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-futbol-o"></i>
          </div>
        </div> 
        <input id="ordrenr" name="ordrenr" type="text" class="form-control" required="required">
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="ordredato" class="col-4 col-form-label">Ordredato</label> 
    <div class="col-8">
      <input id="ordredato" name="ordredato" type="text" required="required" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="cvre" class="col-4 col-form-label">CVR</label> 
    <div class="col-8">
      <input id="cvre" name="cvre" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="firmanavn" class="col-4 col-form-label">Firmanavn</label> 
    <div class="col-8">
      <input id="firmanavn" name="firmanavn" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="voresref" class="col-4 col-form-label">Vores reference</label> 
    <div class="col-8">
      <input id="voresref" name="voresref" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="cpr" class="col-4 col-form-label">CPR</label> 
    <div class="col-8">
      <input id="cpr" name="cpr" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="textarea" class="col-4 col-form-label">Adresse</label> 
    <div class="col-8">
      <textarea id="textarea" name="textarea" cols="40" rows="5" class="form-control"></textarea>
    </div>
  </div>
  <div class="form-group row">
    <label for="postnr" class="col-4 col-form-label">Post nr</label> 
    <div class="col-8">
      <input id="postnr" name="postnr" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="by" class="col-4 col-form-label">By</label> 
    <div class="col-8">
      <input id="by" name="by" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="oplysningerfrakunde" class="col-4 col-form-label">Oplysninger fra kunde</label> 
    <div class="col-8">
      <textarea id="oplysningerfrakunde" name="oplysningerfrakunde" cols="40" rows="5" class="form-control"></textarea>
    </div>
  </div>
  <div class="form-group row">
    <label for="vileverer" class="col-4 col-form-label">Vi leverer</label> 
    <div class="col-8">
      <textarea id="vileverer" name="vileverer" cols="40" rows="5" class="form-control"></textarea>
    </div>
  </div>
  <div class="form-group row">
    <label for="behovsliste" class="col-4 col-form-label">Behovsliste</label> 
    <div class="col-8">
      <textarea id="behovsliste" name="behovsliste" cols="40" rows="5" class="form-control"></textarea>
    </div>
  </div>
  <div class="form-group row">
    <label for="prisoverslag" class="col-4 col-form-label">Prisoverslag</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">kr excl. moms</div>
        </div> 
        <input id="prisoverslag" name="prisoverslag" type="text" class="form-control">
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="forventetlevering" class="col-4 col-form-label">Forventet levering</label> 
    <div class="col-8">
      <input id="forventetlevering" name="forventetlevering" type="text" class="form-control">
    </div>
  </div>
  <div class="form-group row">
    <label for="status" class="col-4 col-form-label">Status</label> 
    <div class="col-8">
      <input id="status" name="status" type="text" class="form-control">
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
