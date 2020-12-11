<template>
  <v-btn icon @click.stop="downloadFile(request)">
    <v-icon>mdi-printer</v-icon>
  </v-btn>
</template>
<script>
import pdfmake from "pdfmake/build/pdfmake";
export default {
  props: {
    request: {
      required: true
    }
  },
  methods: {
    downloadFile(request) {
      const messages = {
        Оборудования: `Прошу Вас предоставить мне оборудования: ${request.parameters
          .map(el => el.value.toLowerCase())
          .join(", ")}.`,
        Отпуск: `Прошу Вас предоставить мне ежегодный оплачиваемый отпуск в периоде ${request.parameters
          .map(el => el.value)
          .join(" - ")}.`,
        Аванс: `Прошу Вас предоставить мне аванс в размере ${request.parameters[0]} сомони.`,
        Больничный: `Прошу Вас предоставить мне больничный в период ${request.parameters
          .map(el => el.value)
          .join(" - ")}.`,
        Прочее: `${request.description}`
      };
      pdfmake.fonts = {
        Roboto: {
          normal:
            "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Regular.ttf"
        }
      };
      let docDefinition = {
        pageSize: "A4",
        content: [
          {
            stack: [
              "Главе Представительства",
              "«Evolet Healthcare» в Таджикистане",
              "Г-же Мирзоевой С.Ф.",
              `Обращается ${request.user.position_level.name}`,
              `${request.user.division.name}`,
              `${request.user.fullname}`
            ],
            alignment: "right",
            margin: [0, 40]
          },
          {
            text: `Заявление на ${request.type}`,
            alignment: "center",
            margin: [0, 10]
          },
          {
            text: messages[request.type],
            margin: [0, 0]
          },
          {
            text: new Date().toLocaleDateString(),
            alignment: "right",
            margin: [0, 20]
          }
        ],
        defaultStyle: {
          font: "Roboto",
          style: "normal"
        }
      };
      let pdf = pdfmake.createPdf(docDefinition).print();
    }
  }
};
</script>