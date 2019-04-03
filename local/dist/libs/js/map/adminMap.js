BX(function () {
	BX.AdminYMap = function (param) {
		this.params = param;
		this.polyDeleted = false;

		var _this = this;
		var urlCords = '/local/modules/ul.main/tools/ajax/cords.php?getAllCords=Y&sessid=' + BX.bitrix_sessid();

		this.init = function () {
			BX.ajax.loadJSON(urlCords, function (result) {

				_this.Map = new ymaps.Map("ris_map", {
					center: [55.756449, 37.617112],
					zoom: 9,
					behaviors: ['drag', 'rightMouseButtonMagnifier', 'scrollZoom'],
					controls: ['geolocationControl', 'rulerControl', 'zoomControl']
				});


				var isNotEmpty = 0, emptyCordCurrent = false;
				if(result.DATA != null){


					$.each(result.DATA, function (i, value) {
						var polyProp;
						if(!is.empty(value.CORDS)){
							polyProp = JSON.parse(value.CORDS);
						} else {
							polyProp = {
								cords: [[]],
								options: {}
							};
						}

						isNotEmpty++;
						var PolygonSaved = new ymaps.Polygon(polyProp.cords, {}, polyProp.options);
						_this.Map.geoObjects.add(PolygonSaved);

						if (_this.params.ID == value.ID) {
							_this.polygon = PolygonSaved;
							_this.polygon.editor.startDrawing();

							var currentCoords = JSON.parse(value.CORDS);

							_this.Map.setCenter(currentCoords['cords'][0][0], 11);

							$('#addPolygon').hide(0);
						}

						if(is.empty(value.CORDS) && _this.params.ID == value.ID){
							_this.startEdit('#addPolygon');
							$('#addPolygon').show(0);
						}
					});
				}

				if(isNotEmpty == 0 || _this.params.ID == 0){
					_this.startEdit('#addPolygon');
				}
				_this.stopEdit('#stopEditPolygon');
				_this.delEdit('#dellPolygon');
			});
		};

		this.startEdit = function (btn) {
			var $obBtnStart = $(btn);
			if($obBtnStart.is(':visible')){
				$obBtnStart.on('click', function (ev) {
					ev.preventDefault();
					$('#stopEditPolygon').attr('disabled', false);
					$('#addPolygon').attr('disabled', true);
					_this.createNewPolygon();
				});
			}
		};

		this.createNewPolygon = function () {

			var color_polygon = $('#color_polygon').val();
			var fillopacity_polygon = $('#fillopacity_polygon').val();
			var width_line = $('#width_line').val();
			var color_line = $('#color_line').val();
			var opacity_line = $('#opacity_line').val();

			_this.polygon = new ymaps.Polygon([[]], {}, {
				fillColor: color_polygon,
				strokeColor: color_line,
				opacity: fillopacity_polygon,
				strokeOpacity: opacity_line,
				strokeWidth: width_line
			});

			_this.Map.geoObjects.add(_this.polygon);
			_this.polygon.editor.startDrawing();
			_this.polyDeleted = false;
		};

		this.stopEdit = function (btn, Polygon) {
			var $stopBtn = $(btn);
			$stopBtn.on('click', function (ev) {
				ev.preventDefault();
				_this.polygon.editor.stopEditing();
				_this.printGeometry(_this.polygon.geometry.getCoordinates());

				$('#stopEditPolygon').attr('disabled', true);
			})
		};

		this.delEdit = function (btn) {
			var $delBtn = $(btn);
			$delBtn.on('click', function (ev) {
				ev.preventDefault();
				_this.Map.geoObjects.remove(_this.polygon);
				_this.polyDeleted = true;

				$('#geometry').html('');
				$('#addPolygon').attr('disabled', false).show(0);
				_this.startEdit('#addPolygon');
			});
		};

		this.printGeometry = function (coords) {
			$('#geometry').html('Координаты: ' + stringify(coords));

			var savePoly = {
				cords: coords,
				options: {
					fillColor: _this.polygon.options.get('fillColor'),
					strokeColor: _this.polygon.options.get('strokeColor'),
					opacity: _this.polygon.options.get('opacity'),
					strokeOpacity: _this.polygon.options.get('strokeOpacity'),
					strokeWidth: _this.polygon.options.get('strokeWidth')
				}
			};

			if(!_this.polyDeleted)
				$('#CORDS').text(JSON.stringify(savePoly));
			else
				$('#CORDS').text('');

			function stringify(coords) {
				var res = '';
				if ($.isArray(coords)) {
					res = '[ ';
					for (var i = 0, l = coords.length; i < l; i++) {
						if (i > 0) {
							res += ', ';
						}
						res += stringify(coords[i]);
					}
					res += ' ]';
				} else if (typeof coords == 'number') {
					res = coords.toPrecision(6);
				} else if (coords.toString) {
					res = coords.toString();
				}

				return res;
			}
		}
	};
});