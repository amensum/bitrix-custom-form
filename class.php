<?php

namespace Custom;

use Bitrix\Main\Application,
    Bitrix\Main\Loader,
    Bitrix\Main\Mail\Event,
    CBitrixComponent,
    CFile,
    CIBlockElement;

class Form extends CBitrixComponent
{
    public function executeComponent()
    {
        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->getPost("TOKEN") === $this->arParams["TOKEN"]) {
            $APPLICATION->RestartBuffer();

            $post_list = $request->getPostList()->toArray();
            $file_list = $request->getFileList()->toArray();
            $props = array_merge($post_list, $file_list);

            if ($this->arParams["RECAPTCHA_ENABLED"] === 'Y') {
                if (!$this->recaptcha($post_list["RECAPTCHA"])) {
                    $this->out([
                        "status" => false,
                        "message" => "Не пройдена проверка reCAPTCHA"
                    ]);
                }
            }

            $available_props = $this->arParams["PROPS"];
            $iblock_id = $this->arParams["IBLOCK_ID"];
            $save_r = $this->save($iblock_id, $props, $available_props);
            $iblock_el_id = $save_r["ID"];

            if ($save_r["status"] === false) {
                $this->out($save_r);
            }

            $db_list = CIBlockElement::GetList(
                ["SORT" => "ASC"],
                [
                    "IBLOCK_ID" => $iblock_id,
                    "ID" => $iblock_el_id
                ]
            );

            if ($db_el = $db_list->GetNextElement()) {
                $iblock_el_props = $db_el->GetProperties();

                $event_params = [
                    "EVENT_NAME" => $this->arParams["MAIL_EVENT"],
                    "LID" => SITE_ID,
                    "C_FIELDS" => [
                        "EMAIL_TO" => $this->arParams["EMAIL_TO"],
                        "EMAIL_FROM" => $this->arParams["EMAIL_FROM"]
                    ]
                ];

                foreach ($iblock_el_props as $code => $prop) {
                    if ($prop["PROPERTY_TYPE"] === "F") {
                        $tag = CFile::GetPath($prop["VALUE"]);
                        $event_params["C_FIELDS"][$code] = $tag;
                        continue;
                    }

                    $event_params["C_FIELDS"][$code] = $prop["VALUE"];
                }

                Event::send($event_params);
            }

            $this->out(["status" => true, "message" => "Форма успешно отправлена"]);
        }

        $this->includeComponentTemplate();
    }

    private function recaptcha($recaptcha)
    {
        $url = "https://www.google.com/recaptcha/api/siteverify?secret="
            . $this->arParams["RECAPTCHA_PRIVATE_KEY"]
            . "&response="
            . $recaptcha
            . "&remoteip="
            . $_SERVER["REMOTE_ADDR"];
        $response_data = file_get_contents($url);
        $response_json = json_decode($response_data, true);

        return $response_json["success"] && $response_json["score"] >= 0.5 && $response_json["action"] === "feedback";
    }

    private function save($iblock_id, $props, $available_props)
    {

        if (!Loader::includeModule("iblock")) {
            return [
                "status" => false,
                "message" => "Не удалось подключить модуль 'iblock'"
            ];
        };

        $el = new CIBlockElement;
        $fields = [
            "NAME" => !empty($this->arParams["FORM_NAME"]) ? $this->arParams["FORM_NAME"] : "Форма",
            "IBLOCK_ID" => $iblock_id,
            "PROPERTY_VALUES" => []
        ];
        if (isset($this->arParams["ACTIVE"])) {
            $fields["ACTIVE"] = $this->arParams["ACTIVE"];
        }
        foreach ($props as $key => $prop) {
            if (in_array($key, $available_props)) {
                $fields["PROPERTY_VALUES"][$key]["VALUE"] = $prop;
            } else if (in_array("$key,TEXT", $available_props)) {
                $fields["PROPERTY_VALUES"][$key]["VALUE"] = [
                    "TYPE" => "TEXT",
                    "TEXT" => $prop
                ];
            } else if (in_array("$key,HTML", $available_props)) {
                $fields["PROPERTY_VALUES"][$key]["VALUE"] = [
                    "TYPE" => "HTML",
                    "TEXT" => $prop
                ];
            } else if (in_array("$key,FILE", $available_props)) {
                $fields["PROPERTY_VALUES"][$key] = $_FILES[$key];
            } else if (in_array("$key,FILES", $available_props)) {
                $fields["PROPERTY_VALUES"][$key] = $this->normalizeFiles($_FILES[$key]);
            }
        }
        $id = $el->Add($fields);

        if ($id === false) {
            return [
                "status" => false,
                "message" => "Не удалось создать элемент",
                "LAST_ERROR" => $el->LAST_ERROR
            ];
        }

        return [
            "status" => true,
            "message" => "Элемент успешно создан",
            "ID" => $id
        ];
    }

    private function normalizeFiles($vector)
    {
        $result = [];

        foreach ($vector as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $result[$key2][$key1] = $value2;
            }
        }

        return $result;
    }

    private function out($output): void
    {
        echo json_encode($output);
        exit();
    }
}
