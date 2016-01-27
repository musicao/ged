 
	function calculoVerificador1(noveDigitos){
		var soma = null;

		for (var j = 0; j < 9; ++j) {
			soma += noveDigitos.toString().charAt(j)*(10-j);
		}

		var somaFinalVerificador1 = soma % 11,
			verificador1 = 11 - somaFinalVerificador1;

		if (somaFinalVerificador1 < 2) {
			verificador1 = 0;
		}

		return verificador1;
	}

	function calculoVerificador2(cpfComVerificador1){
		var soma = null;

		for (var k = 0; k < 10; ++k){
			soma +=  cpfComVerificador1.toString().charAt(k)*(11-k);
		}

		var somaFinalVerificador2 = soma % 11,
			verificador2 = 11 - somaFinalVerificador2;

		if (somaFinalVerificador2 < 2) {
			verificador2 = 0;
		}

		return verificador2;
	}

	function limpa(valor){
		var digitos = valor.replace(/\.|\-|\s/g,'');

		return digitos;
	}

	 
        function valida(valor){
            
		var clearCPF = limpa(valor),
			noveDigitos = clearCPF.substring(0,9),
			verificadores = clearCPF.substring(9,11);

		//Verificando se todos os digitos são iguais
		for (var i = 0; i < 10; i++){
			if('' + noveDigitos + verificadores === Array(12).join( i ) ){
				return false;
			}
		}

		var verificador1 = calculoVerificador1(noveDigitos);

		var verificador2 = calculoVerificador2(noveDigitos + '' + verificador1);

		if (verificadores.toString() === verificador1.toString() + verificador2.toString()){
			return true;
		}else{
			return false;
		}
	};

	 