<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mail</title>
    <style>
        @media only screen and (max-width: 600px) {
        .main {
            width: 320px !important;
        }

        .top-image {
            width: 100% !important;
        }
        .inside-footer {
            width: 320px !important;
        }
        table[class="contenttable"] {
            width: 320px !important;
            text-align: left !important;
        }
        td[class="force-col"] {
            display: block !important;
        }
        td[class="rm-col"] {
            display: none !important;
        }
        .mt {
            margin-top: 15px !important;
        }
        *[class].width300 {
            width: 255px !important;
        }
        *[class].block {
            display: block !important;
        }
        *[class].blockcol {
            display: none !important;
        }
        .emailButton {
            width: 100% !important;
        }

        .emailButton a {
            display: block !important;
            font-size: 18px !important;
        }
        }
    </style>
</head>
<body link="#00a5b5" vlink="#00a5b5" alink="#00a5b5">

    <table class="main contenttable" style="text-align:center;font-weight: normal;border-collapse: collapse;border: 0;margin-left: auto;margin-right: auto;padding: 0;font-family: Arial, sans-serif;color: #555559;background-color: white;font-size: 16px;line-height: 26px;width: 600px;">
      <tr>
        <td class="border" style="border-collapse: collapse;border: 1px solid #eeeff0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
          <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
            <tr>
              <td colspan="4" valign="top" class="image-section" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background-color: #fff;border-bottom: 4px solid #005ca7">
                <!--<a href="https://www.emsaba.gob.ec"><img class="top-image" src="/assets/viewmain/mail/Logo-Emsaba.png" style="line-height: 1;width: 325px;margin: 7px;" alt="EMSABA EP"></a>-->
              </td>
            </tr>
            <tr>
              <td valign="top" class="side title" style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;vertical-align: top;background-color: white;border-top: none;">
                <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                  <tr>
                    <td class="head-title" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 28px;line-height: 34px;font-weight: bold; text-align: center;">
                      <div class="mktEditable" id="main_title">
                        Buzón de Atención Ciudadana
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="sub-title" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;padding-top:5px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 18px;line-height: 29px;font-weight: bold;text-align: center;">
                      <div class="mktEditable" id="intro_title">
                        
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="top-padding" style="border-collapse: collapse;border: 0;margin: 0;padding: 5px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;"></td>
                  </tr>
                  <tr>
                    <td class="grey-block" style="border-collapse: collapse;border: 0;margin: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background-color: #fff; text-align:center;">
                      <div class="mktEditable" id="cta" style="text-align: end;font-size: 12.5px;">
                        <strong>Fecha:</strong> {{$fecha}}<br>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="top-padding" style="border-collapse: collapse;border: 0;margin: 0;padding: 15px 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 21px;">
                      <hr size="1" color="#eeeff0">
                    </td>
                  </tr>
                  <tr>
                    <td class="text" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                      <div class="mktEditable" id="main_text">
                        <p style="text-align: left;font-size: 17px;font-weight: 600;">Datos del Remitente</p>
                        <ul style="text-align: left;font-size: 16px;">
                          <li><span style="margin-right: 10px;"><b>Nombres:</b></span> {{$nombre}}</li>
                          <li><span style="margin-right: 10px;"><b>Email:</b></span>{{$email}}</li>
                          <li><span style="margin-right: 10px;"><b>Teléfono:</b></span>{{$telefono}}</li>
                          <li><span style="margin-right: 10px;"><b>Cuenta:</b></span>{{$cuenta}}</li>
                        </ul>
                        <p style="text-align: left;font-size: 16px;font-weight: 600;">EMSABA EP,</p>
                        <p style="text-align: justify;">{{$detalle}}</p>
                        
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td style="padding:0px 20px 20px 20px; font-family: Arial, sans-serif; -webkit-text-size-adjust: none;text-align:center;">
                <table>
                  <tr>
                    <td style="text-align:center;font-family: Arial, sans-serif; -webkit-text-size-adjust: none; font-size: 16px;">
                      <span style="text-align: left;font-size:12px; font-family: Arial, sans-serif; -webkit-text-size-adjust: none;">Por favor, no responda a este correo.</span>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr style="background-color:#fff;border-top: 4px solid #005ca7;">
              <td valign="top" class="footer" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background: #fff;text-align: center;">
                <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                  <tr>
                    <td class="inside-footer" valign="middle" style="text-align:center;border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">
                      <div id="address" class="mktEditable">
                        <b>EMPRESA PÚBLICA MUNICIPAL DE SANEAMIENTO AMBIENTAL DE BABAHOYO</b><br>
                        <!--<a style="color: #005ca7;" href="https://www.emsaba.gob.ec">Visítanos</a>-->
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
</body>
</html>