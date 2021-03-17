/*
    1º) Separei as responsabilidades do código em pequenas funções assim facilitando o entendimento 
    2º) Usei uma técnica chamada early return onde caso não atenda a condição do if já faço um return rápido evitando percorrer todo código
    3º) Como o pré requisito era não utilizar as funções de data que facilitam, tive que fazer tudo na mão.
*/

/* O retorno é um int (true = verdadeiro ou false = falso) */
public function calculaDias($dataInicial, $dataFinal) 
{
    const DIA = 60*60*24;
  
    $mensagem = "";
    $quantidadeDias = 0

    if (empty($dataInicial) || empty($dataFinal)) {
        $mensagem = "As datas não foram informadas ou são inválidas";
        return $quantidadeDias;
    }
    
    if ( !$this->verificaSeDataFinalMaiorQueDataInicial($dataInicial, $dataFinal) ) {
        $mensagem = "A data final não pode ser menor que data inicial";
        return $quantidadeDias;
    }

    $diff = abs(strtotime($dataFinal) - strtotime($dataInicial));

    $newAno = floor($diff / (365*self::DIA));
    $newMes = floor(($diff - $ano * 365*self::DIA) / (30*self::DIA)) ;
    $newDia = floor(($diff - $ano * 365*self::DIA - $mes*30*self::DIA) / (self::DIA)) ;
    
    /* Mensagem final ao usuário */
    echo "A quandidade de dias é {$quantidadeDias}";
    return $quantidadeDias;
}


/* O retorno é um booleano (true = verdadeiro ou false = falso) */
public function verificaSeDataFinalMaiorQueDataInicial( $dataInicial, $dataFinal ) 
{
    /* Separando a string e convertendo em inteiro*/
    $anoDataInicial = intval(substr($dataInicial, 0, 3)); # Separando a string em YYYY e convertendo em inteiro
    $mesDataInicial = intval(substr($dataInicial, 5, 6)); # Separando a string em MM e convertendo em inteiro
    $diaDataInicial = intval(substr($dataInicial, 8, 9)); # Separando a string em DD e convertendo em inteiro
    if ( !$this->verificaAnoBissexto($anoDataInicial) ) {
        echo "O {$anoDataInicial} não é bissexto";
    }  

    /* Separando a string e convertendo em inteiro*/
    $anoDataFinal = intval(substr($dataFinal, 0, 3));  # Separando a string em YYYY e convertendo em inteiro
    $mesDataFinal = intval(substr($dataFinal, 5, 6));  # Separando a string em MM e convertendo em inteiro
    $diaDataFinal = intval(substr($dataFinal, 8, 9));  # Separando a string em DD e convertendo em inteiro
    if ( !$this->verificaAnoBissexto($anoDataFinal) ) {
        echo "O {$anoDataFinal} não é bissexto";
    }  

    if ( $anoDataFinal < $anoDataInicial) {
        return false;
    }

    if ( $mesDataFinal <= $mesDataInicial) {
        if ( !$diaDataFinal >= $diaDataInicial) {
            return false;
        }
    }

    return true;
}


/* O retorno é um booleano (true = verdadeiro ou false = falso) */
public function verificaAnoBissexto($ano) 
{   
    if (empty($ano)) {
        echo "O {$ano} informado é inválido";
        return false;
    }
        
    if ( !(($ano % 4) == 0 && ($ano % 100) != 0) || ($ano % 400) == 0 ) {
        return false;
    }

    return true;
}